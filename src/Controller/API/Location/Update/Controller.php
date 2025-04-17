<?php

namespace App\Controller\API\Location\Update;

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

    #[OA\Tag(name: 'Location')]
    #[OA\Put(
        description: 'Updates a location by ID',
        summary: 'Update a location by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the location to update'),
        ],
        requestBody: new OA\RequestBody(
            description: 'Location data',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: InputLocationDTO::class))
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Location updated successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputLocationDTO::class))
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
    #[RequireRole(roles: [UserRole::ROLE_ADMIN->value])]
    #[Route('/api/location/{id}', name: 'api_location_update', methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputLocationDTO $dto): OutputLocationDTO
    {
        return $this->manager->update($id, $dto);
    }
}
