<?php

namespace App\Controller\API\Review\Delete;

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

    #[OA\Tag(name: 'Review')]
    #[OA\Delete(
        description: 'Deletes a review by ID',
        summary: 'Delete a review by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the review to delete'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Review not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_PASSENGER->value, UserRole::ROLE_ADMIN->value])]
    #[Route('/api/review/{id}', name: 'api_review_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        $this->manager->deleteReview($id);

        return $this->json(['message' => 'User deleted successfully']);
    }
}
