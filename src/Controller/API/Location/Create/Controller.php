<?php

namespace App\Controller\API\Location\Create;

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

    #[RequireRole(roles: [UserRole::ROLE_ADMIN->value])]
    #[Route('/api/location', name: 'api_location_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputLocationDTO $dto): OutputLocationDTO
    {
        return $this->manager->create($dto);
    }
}
