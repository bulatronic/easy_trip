<?php

namespace App\Domain\Model\User;

class CreateUserModel
{
    public function __construct(
        public string $username,
        public string $email,
        public string $password,
        public string $role,
    ) {
    }
}
