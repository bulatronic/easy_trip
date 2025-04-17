<?php

namespace App\Controller\API\Trip\Get;

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

    #[OA\Tag(name: 'Trip')]
    #[OA\Get(
        description: 'Retrieves a trip by ID',
        summary: 'Get a trip by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the trip to retrieve'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Trip retrieved successfully',
                content: new OA\JsonContent(
                    ref: new Model(type: OutputTripDTO::class)
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Trip not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_USER->value])]
    #[Route('/api/trip/{id}', name: 'api_trip_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputTripDTO
    {
        return $this->manager->get($id);
    }
}
