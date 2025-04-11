<?php

namespace App\Controller\API\Review\Update;

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
    #[Route('/api/review/{id}', name: 'api_review_update', methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputReviewDTO $dto): OutputReviewDTO
    {
        return $this->manager->update($id, $dto);
    }
}
