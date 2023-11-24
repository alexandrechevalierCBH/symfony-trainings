<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    private string $appUrl;
    private string $showSingleUrl;

    public function __construct(ParameterBagInterface $parameterBagInterface)
    {
        $this->appUrl = $parameterBagInterface->get('APP_URL');
        $this->showSingleUrl = sprintf(
            '%s%s',
            $this->appUrl,
            '/group/show/'
        );
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
            new TwigFilter('ucWords', [$this, 'ucWords']),
            new TwigFilter('linkFromSlug', [$this, 'linkFromSlug']),
            new TwigFilter('nextExpenses', [$this, 'nextExpenses']),
            new TwigFilter('previousExpenses', [$this, 'previousExpenses']),
        ];
    }

    public function formatPrice(float $number, string $currency = 'euros', int $decimals = 2, string $decPoint = ',', string $thousandsSep = ' '): string
    {
        $symbol = [
            'euros' => ' €',
            'dollars' => '$',
            'pounds' => '£',
        ];

        $price = number_format($number / 100, $decimals, $decPoint, $thousandsSep).$symbol[$currency];

        return $price;
    }

    public function ucWords(string $stringToCapitalize): string
    {
        return ucwords($stringToCapitalize);
    }

    public function linkFromSlug(string $slug): string
    {
        return sprintf(
            '%s%s',
            $this->showSingleUrl,
            $slug
        );
    }

    public function nextExpenses(string $url, int $page, int $step): string
    {
        return sprintf(
            '%s%s%s',
            $url,
            "?page=$page",
            "&step=$step",
        );
    }

    public function previousExpenses(string $url, int $page, int $step): string
    {
        return sprintf(
            '%s%s%s',
            $url,
            '?page='.($page - 2),
            "&step=$step"
        );
    }
}
