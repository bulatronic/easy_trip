<?php

namespace App\Application\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class JwtEncoder
{
    private string $secret;
    private string $algorithm;
    private int $tokenTtl;

    public function __construct(
        ParameterBagInterface $params,
        string $secret,
        string $algorithm,
        int $tokenTtl,
    ) {
        $this->secret = $secret;
        $this->algorithm = $algorithm;
        $this->tokenTtl = $tokenTtl;
    }

    public function getTokenTtl(): int
    {
        return $this->tokenTtl;
    }

    public function encode(array $data): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->tokenTtl;

        $tokenData = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'data' => $data,
        ];

        return JWT::encode($tokenData, $this->secret, $this->algorithm);
    }

    public function decode(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));

            return (array) $decoded->data;
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid token');
        }
    }
}
