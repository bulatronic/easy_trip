<?php

namespace App\Controller\API\Location\Get;

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

    #[RequireRole(roles: [UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value])]
    #[Route('/api/location/{id}', name: 'api_location_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputLocationDTO
    {
        return $this->manager->get($id);
    }
}
