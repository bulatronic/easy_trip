<?php

namespace App\Controller\API\Trip\Update;

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

    #[RequireRole(roles: [UserRole::ROLE_DRIVER->value])]
    #[Route('/api/trip/{id}', name: 'api_trip_update', methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputTripDTO $dto): OutputTripDTO
    {
        return $this->manager->update($id, $dto);
    }
}
