<?php

namespace App\Controller\API\Auth;

use App\Controller\Security\RequireRole;
use App\Domain\Entity\User;
use App\Domain\ValueObject\UserRole;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    #[OA\Tag(name: 'Auth')]
    #[OA\Get(
        description: 'Get the current user',
        summary: 'Get the current user',
        responses: [
            new OA\Response(
                response: 200,
                description: 'User retrieved successfully',
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
        ]
    )]
    #[RequireRole(roles: [UserRole::ROLE_USER->value])]
    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('User not found');
        }

        return $this->json([
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }
}
