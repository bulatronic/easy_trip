<?php

namespace App\Controller\API\User\Get;

use App\Domain\Service\UserService;

readonly class Manager
{
    public function __construct(
        private UserService $service,
    ) {
    }

    public function get(int $id): OutputUserDTO
    {
        $user = $this->service->findUserById($id);

        return new OutputUserDTO(
            id: $user->getId(),
            username: $user->getUsername(),
            email: $user->getEmail(),
            role: $user->getRole(),
        );
    }
}
