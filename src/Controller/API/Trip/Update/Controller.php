<?php

namespace App\Controller\API\Trip\Update;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[OA\Tag(name: 'Trip')]
    #[OA\Put(
        description: 'Updates a trip by ID',
        summary: 'Update a trip by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the trip to update'),
        ],
        requestBody: new OA\RequestBody(
            description: 'Trip data',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: InputTripDTO::class))
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Trip updated successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputTripDTO::class))
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'type', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Error message'),
                    ]
                )
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_DRIVER->value])]
    #[Route('/api/trip/{id}', name: 'api_trip_update', methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputTripDTO $dto): OutputTripDTO
    {
        return $this->manager->update($id, $dto);
    }
}
