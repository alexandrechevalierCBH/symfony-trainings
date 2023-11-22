<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Repository\ExpenseRepository;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SingleController extends AbstractController
{
    #[Route('group/show/{slug}', methods: ['GET'], name: 'group_show')]
    public function show(GroupRepository $repo, string $slug, ExpenseRepository $expenseRepo, Request $request): Response
    {
        $queryPage = $request->get('page') ?? 1;
        $queryStep = $request->get('step') ?? 10;

        /** @var int $page */
        $page = $queryPage;

        /** @var int $step */
        $step = $queryStep;

        $group = $repo->findOneBySlug($slug);

        if (!$group instanceof Group) {
            throw new NotFoundHttpException('The group was not found');
        }

        $expenses = $expenseRepo->findAndPaginateExpenses($group, $page, $step);

        return $this->render('Group/single.html.twig', [
            'group' => $group,
            'tenExpenses' => $expenses,
            'page' => $page + 1,
            'step' => $step,
        ]);
    }
}
