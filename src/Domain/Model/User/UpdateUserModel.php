<?php

namespace App\Domain\Model\User;

use App\Domain\Entity\User;

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

    public function updateUser(User $user): void
    {
        if (null !== $this->username) {
            $user->setUsername($this->username);
        }
        if (null !== $this->email) {
            $user->setEmail($this->email);
        }
        if (null !== $this->role) {
            $user->setRole($this->role);
        }
        if (null !== $this->password) {
            $user->setPassword($this->password);
        }
        $user->setUpdatedAt();
    }
}
