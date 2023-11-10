<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Form\Type\GroupType;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @phpstan-import-type GroupTypeFormData from GroupType
 */
class CreateController extends AbstractController
{
    #[Route('group/create', methods: ['GET', 'POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $time = time();
        $slugify = new Slugify();

        $form = $this->createForm(GroupType::class, ['time' => $time]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var GroupTypeFormData $data */
            $data = $form->getData();

            $label = $data['label'];
            $members = $data['persons']->toArray();
            $description = $data['description'] ?? null;

            $group = new Group($label, $members, $description);

            $group->setSlug(sprintf('%s-%s', $time, $slugify->slugify($data['slug'])));

            try {
                $em->persist($group);
                $em->flush();

                $this->addFlash('success', 'Le groupe a été créé avec succès !');

                return $this->redirectToRoute('group_show', [
                    'slug' => $group->getSlug(),
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
