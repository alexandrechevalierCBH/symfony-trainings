<?php

namespace App\Controller\Group;

use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route('groups', methods: ['GET'], name: 'group_list')]
    public function groups(GroupRepository $repo): Response
    {
        $groups = $repo->findAllAndOrderByLastExpense();

        return $this->render('Group/list.html.twig', [
            'groups' => $groups,
        ]);
    }
}
