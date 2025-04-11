<?php

namespace App\Controller\API\Booking\Get;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[RequireRole(roles: [UserRole::ROLE_USER->value])]
    #[Route('/api/booking/{id}', name: 'api_booking_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputBookingDTO
    {
        return $this->manager->get($id);
    }
}
