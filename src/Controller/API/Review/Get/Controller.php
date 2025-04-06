<?php

namespace App\Controller\API\Review\Get;

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
    #[Route('/api/review/{id}', name: 'api_review_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputReviewDTO
    {
        return $this->manager->get($id);
    }
}
