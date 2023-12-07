<?php

namespace App\Controller\Expense;

use App\Entity\Expense;
use App\Form\Type\EditExpenseType;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class EditController extends AbstractController
{
    #[Route('expense/edit/{id}', methods: ['GET', 'PUT'], name: 'expense_edit')]
    public function edit(ExpenseRepository $expenseRepository, Uuid $id, Request $request, EntityManagerInterface $em): Response
    {
        $expense = $expenseRepository->findByUuid($id);

        if (!$expense instanceof Expense) {
            throw new NotFoundHttpException('The expense was not found');
        }

        $form = $this->createForm(
            EditExpenseType::class,
            $expense, [
                'method' => 'PUT',
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();

                $this->addFlash('success', 'La dépense a été modifiée avec succès !');

                return $this->redirectToRoute('group_show', [
                    'slug' => $expense->getGroup()->getSlug(),
                ]);
            } catch (\Exception) {
                $this->addFlash('error', 'La modification de la dépense a échoué');
            }
        }

        return $this->render('Forms/Expense/edit.html.twig', [
            'editExpenseForm' => $form->createView(),
        ]);
    }
}
