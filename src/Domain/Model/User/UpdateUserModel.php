<?php

namespace App\Domain\Model\User;

class UpdateUserModel
{
    public function __construct(
        public ?string $username,
        public ?string $email,
        public ?string $password,
        public ?string $role,
        public int $id,
    ) {
    }
}
