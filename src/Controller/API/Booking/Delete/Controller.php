<?php

namespace App\Controller\API\Booking\Delete;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[RequireRole(roles: [UserRole::ROLE_PASSENGER->value, UserRole::ROLE_ADMIN->value])]
    #[Route('/api/booking/{id}', name: 'api_booking_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        $this->manager->deleteBooking($id);

        return $this->json(['message' => 'User deleted successfully']);
    }
}
