<?php

namespace App\Controller\API\Location\Get;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[OA\Tag(name: 'Location')]
    #[OA\Get(
        description: 'Retrieves a location by ID',
        summary: 'Get a location by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the location to retrieve'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Location retrieved successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputLocationDTO::class))
            ),
            new OA\Response(
                response: 404,
                description: 'Location not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value])]
    #[Route('/api/location/{id}', name: 'api_location_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputLocationDTO
    {
        return $this->manager->get($id);
    }
}
