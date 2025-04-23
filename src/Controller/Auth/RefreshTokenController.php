<?php

namespace App\Controller\Auth;

use App\Application\Security\TokenManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RefreshTokenController extends AbstractController
{
    public function __construct(
        private readonly TokenManager $tokenManager,
    ) {
    }

    #[Route('/api/refresh-token', name: 'api_refresh_token', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['refresh_token'])) {
            return new JsonResponse([
                'message' => 'Refresh token is required',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $accessToken = $this->tokenManager->refreshAccessToken($data['refresh_token']);

            return new JsonResponse([
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ]);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse([
                'message' => 'Invalid refresh token',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
