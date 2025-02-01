<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;

/**
 * @extends AbstractRepository<User>
 */
class UserRepository extends AbstractRepository
{
    // поиск пользователей по роли
    public function findByRole(string $role): array
    {
        return $this->entityManager->getRepository(User::class)->findBy([
            'role' => $role,
        ]);
    }

    // поиск пользователей по email
    public function findByEmail(string $email): array
    {
        return $this->entityManager->getRepository(User::class)->findBy([
            'email' => $email,
        ]);
    }
}
