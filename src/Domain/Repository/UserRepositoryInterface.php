<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function create(User $user): int;

    public function update(User $user): int;

    public function remove(User $user): void;

    public function findByRole(string $role): ?array;

    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;
}
