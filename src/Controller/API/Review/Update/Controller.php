<?php

namespace App\Controller\API\Review\Update;

use App\Controller\Security\RequireRole;
use App\Domain\ValueObject\UserRole;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    public function __construct(
        private readonly Manager $manager,
    ) {
    }

    #[OA\Tag(name: 'Review')]
    #[OA\Put(
        description: 'Updates a review by ID',
        summary: 'Update a review by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the review to update'),
        ],
        requestBody: new OA\RequestBody(
            description: 'Review data',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: InputReviewDTO::class))
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review updated successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputReviewDTO::class))
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'type', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Error message'),
                    ]
                )
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_PASSENGER->value])]
    #[Route('/api/review/{id}', name: 'api_review_update', methods: ['PUT'])]
    public function __invoke(int $id, #[MapRequestPayload] InputReviewDTO $dto): OutputReviewDTO
    {
        return $this->manager->update($id, $dto);
    }
}
