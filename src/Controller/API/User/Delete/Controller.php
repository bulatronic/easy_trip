<?php

namespace App\Controller\API\User\Delete;

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

    #[OA\Tag(name: 'User')]
    #[OA\Delete(
        description: 'Deletes a user by ID',
        summary: 'Delete a user by ID',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'The ID of the user to delete'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'User not found'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_ADMIN->value])]
    #[Route('/api/user/{id}', name: 'api_user_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function __invoke(int $id): JsonResponse
    {
        $this->manager->deleteUser($id);

        return $this->json(['message' => 'User deleted successfully']);
    }
}
