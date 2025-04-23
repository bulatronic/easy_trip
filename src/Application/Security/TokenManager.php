<?php

namespace App\Application\Security;

use App\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TokenManager
{
    private JwtEncoder $jwtEncoder;
    private int $refreshTokenTtl;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ParameterBagInterface $params,
        JwtEncoder $jwtEncoder,
        EntityManagerInterface $entityManager,
    ) {
        $this->jwtEncoder = $jwtEncoder;
        $this->refreshTokenTtl = $params->get('jwt_refresh_token_ttl');
        $this->entityManager = $entityManager;
    }

    public function createTokens(User $user): array
    {
        $accessToken = $this->createAccessToken($user);
        $refreshToken = $this->createRefreshToken($user);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_in' => $this->jwtEncoder->getTokenTtl(),
        ];
    }

    public function refreshAccessToken(string $refreshToken): string
    {
        try {
            $data = $this->jwtEncoder->decode($refreshToken);

            if (!isset($data['username']) || !isset($data['type']) || 'refresh' !== $data['type']) {
                throw new \InvalidArgumentException('Invalid refresh token');
            }

            $user = $this->entityManager->getRepository(User::class)
                ->findOneBy(['email' => $data['username']]);

            if (!$user) {
                throw new \InvalidArgumentException('User not found');
            }

            return $this->createAccessToken($user);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid refresh token');
        }
    }

    private function createAccessToken(User $user): string
    {
        return $this->jwtEncoder->encode([
            'username' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'type' => 'access',
        ]);
    }

    private function createRefreshToken(User $user): string
    {
        return $this->jwtEncoder->encode([
            'username' => $user->getEmail(),
            'type' => 'refresh',
        ]);
    }
}
