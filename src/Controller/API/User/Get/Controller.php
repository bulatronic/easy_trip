<?php

namespace App\Controller\API\User\Get;

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

    #[OA\Tag(name: 'User')]
    #[OA\Get(
        description: 'Returns user details for the specified ID',
        summary: 'Get user by ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'User ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User retrieved successfully',
                content: new OA\JsonContent(ref: new Model(type: OutputUserDTO::class))
            ),
            new OA\Response(
                response: 404,
                description: 'User not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_USER->value])]
    #[Route('/api/user/{id}', name: 'api_user_get', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function __invoke(int $id): OutputUserDTO
    {
        return $this->manager->get($id);
    }
}
