<?php

namespace App\Controller\Group;

use App\Form\Type\GroupType;
use App\UseCase\Group\Create\Input;
use App\UseCase\Group\Create\Output;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Mrsuh\JsonValidationBundle\Annotation\ValidateJsonRequest;


/**
 * @phpstan-import-type GroupTypeFormData from GroupType
 */
class CreateController extends AbstractController
{
    /**
     * @validateJsonRequest("../schemas/group/creation-payload.json", methods={"POST"})
     */
    #[Route('group', methods: ['POST'], name: 'group_create')]
    public function create(Request $request, MessageBusInterface $bus, SerializerInterface $serializerInterface): Response
    {
        try {

            $input = $serializerInterface->deserialize($request->getContent(), Input::class, 'json');
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage(), 400);
        }

        try {
            $envelope = $bus->dispatch($input);
            $created = $envelope->last(HandledStamp::class);

            if (null === $created || !$created->getResult() instanceof Output) {
                throw new \Exception('Group creation failed');
            }
            return new JsonResponse($serializerInterface->serialize($created->getResult()->getGroup(), 'json', ['groups' => ['default']]), 201, json: true);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
    }
}
