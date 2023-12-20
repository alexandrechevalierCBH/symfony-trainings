<?php

namespace App\Controller\Expense;

use App\Bus\CommandBus;
use App\Entity\Group;
use App\Event\ExpenseCreatedEvent;
use App\Form\Type\CreateExpenseType;
use App\Repository\GroupRepository;
use App\UseCase\Expense\Create\Input;
use App\UseCase\Expense\Create\InputHigh;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @phpstan-import-type ExpenseTypeFormData from CreateExpenseType
 */
class CreateController extends AbstractController
{
    #[Route('group/{slug}/expense', methods: ['GET', 'POST'], name: 'expense_create')]
    public function create(MessageBusInterface $commandBus, GroupRepository $groupRepo, Request $request, string $slug): Response
    {
        $group = $groupRepo->findOneBySlug($slug);

        if (!$group instanceof Group) {
            throw new \Exception('Group not found');
        }

        $form = $this->createForm(
            CreateExpenseType::class,
            [
                'slug' => $slug,
                'group' => $group,
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ExpenseTypeFormData $data */
            $data = $form->getData();

            $beneficiariesId = array_map(
                static fn ($beneficiary) => $beneficiary->getId(),
                $data['beneficiaries']->toArray()
            );

            if ($data['amount'] > 100) {
                $commandBus->dispatch(
                    new InputHigh(
                        $data['description'],
                        $group->getSlug(),
                        $data['amount'],
                        $data['payer']->getId(),
                        $beneficiariesId
                    )
                );
            } else {

                // try {
                $commandBus->dispatch(
                    new Input(
                        $data['description'],
                        $group->getSlug(),
                        $data['amount'],
                        $data['payer']->getId(),
                        $beneficiariesId
                    )
                );
            }

            $this->addFlash('success', 'La dépense a été créé avec succès !');

            return $this->redirectToRoute('group_show', [
                'slug' => $slug,
            ]);
            // } catch (\Exception) {
            //     $this->addFlash('error', 'La création de la dépense a échoué');
            // }
        }

        return $this->render('Forms/Expense/create.html.twig', [
            'createExpenseForm' => $form->createView(),
            [
                'slug' => $slug,
            ],
        ]);
    }
}
