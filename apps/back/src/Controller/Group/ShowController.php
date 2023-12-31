<?php

namespace App\Controller\Group;

use App\UseCase\Group\Show\Input;
use App\UseCase\Group\Show\Output;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class ShowController extends AbstractController
{
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_STEP = 10;

    #[Route('group/show/{slug}', methods: ['GET'], name: 'group_show')]
    public function show(string $slug, Request $request, MessageBusInterface $bus): Response
    {
        $page = filter_var($request->get('page', self::DEFAULT_PAGE), FILTER_VALIDATE_INT);
        $step = filter_var($request->get('step', self::DEFAULT_STEP), FILTER_VALIDATE_INT);

        if (
            !is_int($step)
            || !is_int($page)
            || !$page
            || !$step
            || $page <= 0
            || $step <= 0
        ) {
            throw new BadRequestException('Invalid parameter');
        }

        $input = new Input(
            $slug,
            $page,
            $step,
        );

        $envelope = $bus->dispatch($input);
        $output = $envelope->last(HandledStamp::class);

        if (null === $output || !$output->getResult() instanceof Output) {
            throw new \Exception('Could not get the group');
        }

        if (null !== $output->getResult()->getFlash()) {
            $this->addFlash('error', $output->getResult()->getFlash());
        }

        return $this->render('Group/single.html.twig', [
            'group' => $output->getResult()->getGroup(),
            'paginatedExpenses' => $output->getResult()->getExpenses(),
            'page' => $page + 1,
            'step' => $step,
        ]);
    }
}
