<?php

namespace App\Controller\Group;

use App\Entity\Group;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route('groups', methods: ['GET'])]
    public function groups(EntityManagerInterface $em): Response
    {
        $groupsRepo = $em->getRepository(Group::class);

        /** @var Collection<int, Group> $groups */
        $groups = $groupsRepo->findAll();

        return $this->render('Group/list.html.twig', [
            'groups' => $groups,
        ]);
    }
}
