<?php

namespace App\Controller\API\Review\Create;

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
    #[OA\Post(
        description: 'Creates a new review with the provided data',
        summary: 'Create a new review',
        requestBody: new OA\RequestBody(
            description: 'Review data',
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: InputReviewDTO::class)
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review created successfully',
                content: new OA\JsonContent(
                    ref: new Model(type: OutputReviewDTO::class)
                )
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
    #[Route('/api/review', name: 'api_review_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputReviewDTO $dto): OutputReviewDTO
    {
        return $this->manager->create($dto);
    }
}
