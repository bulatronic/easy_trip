<?php

namespace App\Controller\API\User\Delete;

use App\Domain\Service\UserService;

readonly class Manager
{
    public function __construct(
        private UserService $service,
    ) {
    }

    public function deleteUser(int $id): void
    {
        $this->service->remove($id);
    }
}
