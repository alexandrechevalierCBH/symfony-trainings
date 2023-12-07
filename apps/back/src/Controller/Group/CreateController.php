<?php

namespace App\Controller\Group;

use App\Form\Type\GroupType;
use App\UseCase\Group\Create\Input;
use App\UseCase\Group\Create\Output;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @phpstan-import-type GroupTypeFormData from GroupType
 */
class CreateController extends AbstractController
{
    #[Route('group/create', methods: ['GET', 'POST'], name: 'group_create')]
    public function create(Request $request, MessageBusInterface $bus): Response
    {
        $time = time();

        $form = $this->createForm(
            GroupType::class,
            [
                'time' => $time,
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var GroupTypeFormData $data */
            $data = $form->getData();

            $personsId = array_map(
                static fn ($member) => $member->getId(),
                $data['persons']->toArray()
            );

            $input = new Input(
                $data['label'],
                $personsId,
                $time,
                $data['description'] ?? null,
            );

            try {
                $envelope = $bus->dispatch($input);
                $created = $envelope->last(HandledStamp::class);

                if (null === $created || !$created->getResult() instanceof Output) {
                    throw new \Exception('Group creation failed');
                }

                $this->addFlash('success', 'Le groupe a été créé avec succès !');

                return $this->redirectToRoute('group_show', [
                    'slug' => $created->getResult()->getGroup()->getSlug(),
                ]);
            } catch (\Exception) {
                $this->addFlash('error', 'La création du groupe a échoué');
            }
        }

        return $this->render('Forms/Group/create.html.twig', [
            'createGroupForm' => $form->createView(),
            'time' => $time,
        ]);
    }
}
