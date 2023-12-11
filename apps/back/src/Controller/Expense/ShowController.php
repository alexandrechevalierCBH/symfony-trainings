<?php

namespace App\Controller\Expense;

use App\Entity\Expense;
use App\Exception\ExpenseNotFoundException;
use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ShowController extends AbstractController
{
    #[Route('expense/show/{id}', methods: ['GET'], name: 'expense_show')]
    public function show(ExpenseRepository $expenseRepository, Uuid $id): Response
    {
        $expense = $expenseRepository->findByUuid($id);

        if (!$expense instanceof Expense) {
            throw new ExpenseNotFoundException($id);
        }

        return $this->render('Expense/single.html.twig', [
            'expense' => $expense,
        ]
        );
    }
}
