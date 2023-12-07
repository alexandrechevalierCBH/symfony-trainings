<?php

namespace App\UseCase\Group\Show;

use App\Controller\Group\ShowController;
use App\Entity\Group;
use App\Repository\ExpenseRepository;
use App\Repository\GroupRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class Handler
{
    public function __construct(private GroupRepository $repo, private ExpenseRepository $expenseRepo)
    {
    }

    public function __invoke(Input $input): Output
    {
        $flash = null;

        $group = $this->repo->findOneBySlug($input->slug);

        if (!$group instanceof Group) {
            throw new NotFoundHttpException('The group was not found');
        }

        $expenses = $this->expenseRepo->findAndPaginateExpenses($group, $input->page, $input->step);

        if (1 !== $input->page && null !== $expenses && 0 === count($expenses)) {
            $page = ShowController::DEFAULT_PAGE;
            $step = ShowController::DEFAULT_STEP;
            $expenses = $this->expenseRepo->findAndPaginateExpenses($group, $page, $step);
            $flash = 'Pas de résultat pour la page demandée. Affichage de la page 1';
        }

        if (null === $expenses) {
            throw new NotFoundHttpException('Could not get the expense list');
        }

        return new Output($group, $expenses, $flash);
    }
}
