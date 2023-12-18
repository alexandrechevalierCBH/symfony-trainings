<?php

namespace App\Controller\Group;

use App\Form\Type\GroupType;
use App\Entity\Group;
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
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @phpstan-import-type GroupTypeFormData from GroupType
 */
class CreateController extends AbstractController
{
    /**
     * @validateJsonRequest("../schemas/group/creation-payload.json", methods={"POST"})
     * 
     * @Operation(
     *     summary="Create a group",
     *     tags={"group"},
     *
     *     @OA\RequestBody (
     *         required=true,
     *
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="label",
     *                 description="Label of the group",
     *                 type="string",
     *         ),
     *
     *             @OA\Property(
     *                 property="description",
     *                 description="Description of the group",
     *                 type="string"
     *             ),
     *
     *             @OA\Property(
     *                 property="personsId",
     *                 description="An array of persons uuid for group members",
     *                 type="array",
     *
     *                 @OA\Items(type="string")
     *             ),
     *         )
     *     ),
     *
     *
     *     @OA\Response(
     *         response="201",
     *         description="Return in case of success",
     *
     *         @OA\JsonContent(
     *             ref=@Model(type=Group::class, groups={"minimal"})
     *         )
     *     )
     * )
     */

    #[Route('/api/group', methods: ['POST'], name: 'group_create')]
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
