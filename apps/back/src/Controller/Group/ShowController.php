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

class ShowController extends AbstractController
{
    private const PAGE = 1;
    private const STEP = 10;

    #[Route('group/show/{slug}', methods: ['GET'], name: 'group_show')]
    public function show(GroupRepository $repo, string $slug, ExpenseRepository $expenseRepo, Request $request): Response
    {
        $page = filter_var($request->get('page', self::PAGE), FILTER_VALIDATE_INT);
        $step = filter_var($request->get('step', self::STEP), FILTER_VALIDATE_INT);

        if (!is_int($page) || $page <= 0) {
            $this->addFlash('error', "Le paramètre 'page' est invalide");
            $page = self::PAGE;
        }

        if (!is_int($step) || $step <= 0) {
            $this->addFlash('error', "Le paramètre 'step' est invalide");
            $step = self::STEP;
        }

        $group = $repo->findOneBySlug($slug);

        if (!$group instanceof Group) {
            throw new NotFoundHttpException('The group was not found');
        }

        $expenses = $expenseRepo->findAndPaginateExpenses($group, $page, $step);

        if (1 !== $page && null !== $expenses && 0 === count($expenses)) {
            $this->addFlash('error', "Pas de résultat pour la page $page. Affichage de la page 1");
            $page = self::PAGE;
            $expenses = $expenseRepo->findAndPaginateExpenses($group, $page, $step);
        }

        return $this->render('Group/single.html.twig', [
            'group' => $group,
            'paginatedExpenses' => $expenses,
            'page' => $page + 1,
            'step' => $step,
        ]);
    }
}
