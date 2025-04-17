<?php

namespace App\Controller\API\Review\Get;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[OA\Tag(name: 'Review')]
    #[OA\Get(
        description: 'Retrieves a review by ID',
        summary: 'Get a review by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the review to retrieve'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review retrieved successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputReviewDTO::class))
            ),
            new OA\Response(
                response: 404,
                description: 'Review not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_USER->value])]
    #[Route('/api/review/{id}', name: 'api_review_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputReviewDTO
    {
        return $this->manager->get($id);
    }
}
