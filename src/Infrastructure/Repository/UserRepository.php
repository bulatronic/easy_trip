<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;

/**
 * @extends AbstractRepository<User>
 */
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function add(User $user): int
    {
        return $this->save($user);
    }

    public function update(User $user): int
    {
        return $this->save($user);
    }

    public function remove(User $user): void
    {
        $this->delete($user);
    }

    // поиск пользователей по роли
    public function findByRole(string $role): ?array
    {
        return $this->em->getRepository(User::class)->findBy([
            'role' => $role,
        ]);
    }

    // поиск пользователей по email
    public function findByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);
    }

    // поиск пользователя по id
    public function findById(int $id): ?User
    {
        return $this->em->getRepository(User::class)->find($id);
    }
}
