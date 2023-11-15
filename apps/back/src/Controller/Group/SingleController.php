<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class SingleController extends AbstractController
{
    #[Route('group/show/{slug}', methods: ['GET'], name: 'group_show')]
    public function playground(GroupRepository $repo, string $slug): Response
    {
        $group = $repo->findOneBySlug($slug);

        if (!$group instanceof Group) {
            throw new NotFoundHttpException('The group was not found');
        }

        return $this->render('Group/single.html.twig', [
            'group' => $group,
        ]);
    }
}
