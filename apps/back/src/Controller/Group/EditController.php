<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Form\Type\EditGroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{
    #[Route('group/edit/{slug}', methods: ['GET', 'PUT'], name: 'group_edit')]
    public function edit(GroupRepository $repo, EntityManagerInterface $em, string $slug, Request $request): Response
    {
        $group = $repo->findOneBySlug($slug);

        if (!$group instanceof Group) {
            throw new NotFoundHttpException('The group was not found');
        }

        $form = $this->createForm(
            EditGroupType::class,
            $group,
            [
                'method' => 'PUT',
            ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                $em->persist($group);
                $em->flush();

                $this->addFlash('success', 'Le groupe a été modifié avec succès !');

                return $this->redirectToRoute('group_show', [
                    'slug' => $group->getSlug(),
                ]);
            } catch (\Exception) {
                $this->addFlash('error', 'La modification du groupe a échoué');
            }
        }

        return $this->render('Forms/Group/edit.html.twig', [
            'editGroupForm' => $form->createView(),
        ]);
    }
}
