<?php

namespace App\Controller\Group;

use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ListController extends AbstractController
{
    #[Route('groups', methods: ['GET'], name: 'group_list')]
    public function groups(GroupRepository $repo, SerializerInterface $serializerInterface): Response
    {
        $groups = $repo->findAllAndOrderByLastExpense();
        return new JsonResponse($serializerInterface->serialize($groups, 'json', ['groups' => [
            "default"
        ]]));
    }
}
