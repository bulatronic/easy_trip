<?php

namespace App\Controller\API\Booking\Update;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[RequireRole(roles: [UserRole::ROLE_PASSENGER->value])]
    #[Route('/api/booking/{id}', name: 'api_booking_update', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputBookingDTO $dto): OutputBookingDTO
    {
        return $this->manager->update($id, $dto);
    }
}
