<?php

namespace App\Controller\API\Booking\Delete;

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

    #[OA\Tag(name: 'Booking')]
    #[OA\Delete(
        description: 'Deletes a booking by ID',
        summary: 'Delete a booking by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the booking to delete'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Booking deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Booking not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_PASSENGER->value, UserRole::ROLE_ADMIN->value])]
    #[Route('/api/booking/{id}', name: 'api_booking_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        $this->manager->deleteBooking($id);

        return $this->json(['message' => 'User deleted successfully']);
    }
}
