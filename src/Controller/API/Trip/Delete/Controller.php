<?php

namespace App\Controller\API\Trip\Delete;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[OA\Tag(name: 'Trip')]
    #[OA\Delete(
        description: 'Deletes a trip by ID',
        summary: 'Delete a trip by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the trip to delete'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Trip deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Trip not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_DRIVER->value, UserRole::ROLE_ADMIN->value])]
    #[Route('/api/trip/{id}', name: 'api_trip_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        $this->manager->deleteTrip($id);

        return $this->json(['message' => 'Trip deleted successfully']);
    }
}
