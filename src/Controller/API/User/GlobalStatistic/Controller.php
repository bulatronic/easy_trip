<?php

namespace App\Controller\API\User\GlobalStatistic;

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

    #[OA\Tag(name: 'Statistics')]
    #[OA\Post(
        description: 'Get global statistics',
        summary: 'Get global statistics',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Global statistics retrieved successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputStatisticDTO::class))
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
    #[RequireRole(roles: [UserRole::ROLE_ADMIN->value])]
    #[Route('/api/statistics/global', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] InputStatisticDTO $dto): OutputStatisticDTO
    {
        return $this->manager->get($dto);
    }
}
