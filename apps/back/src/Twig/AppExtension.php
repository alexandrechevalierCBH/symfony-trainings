<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
            new TwigFilter('ucWords', [$this, 'ucWords']),
            new TwigFilter('linkFromSlug', [$this, 'linkFromSlug']),
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
            sprintf('%s%s', $_ENV['APP_URL'], '/group/show/'),
            $slug
        );
    }
}
