<?php

namespace App\Controller\API\Trip\Delete;

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

    #[RequireRole(roles: [UserRole::ROLE_DRIVER->value, UserRole::ROLE_ADMIN->value])]
    #[Route('/api/trip/{id}', name: 'api_trip_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        $this->manager->deleteTrip($id);

        return $this->json(['message' => 'Trip deleted successfully']);
    }
}
