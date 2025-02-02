<?php

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Model\User\CreateUserModel;
use App\Domain\Model\User\UpdateUserModel;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(CreateUserModel $model): User
    {
        $user = new User();
        $user->setUsername($model->username);
        $user->setEmail($model->email);
        $user->setPassword($model->password);
        $user->setRole($model->role);
        $user->setCreatedAt();
        $this->userRepository->create($user);

        return $user;
    }

    public function remove(int $id): void
    {
        $user = $this->getUserOrFail($id);
        $this->userRepository->remove($user);
    }

    public function update(int $id, UpdateUserModel $model): User
    {
        $user = $this->getUserOrFail($id);

        if (null !== $model->username) {
            $user->setUsername($model->username);
        }
        if (null !== $model->email) {
            $user->setEmail($model->email);
        }
        if (null !== $model->role) {
            $user->setRole($model->role);
        }
        if (null !== $model->password) {
            $user->setPassword($model->password);
        }

        $user->setUpdatedAt();

        $this->userRepository->update($user);

        return $user;
    }

    public function findUserById(int $id): ?User
    {
        return $this->getUserOrFail($id);
    }

    private function getUserOrFail(int $id): User
    {
        $user = $this->userRepository->findById($id);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('User with id %d not found.', $id));
        }

        return $user;
    }

    public function findByRole(string $role): array
    {
        $user = $this->userRepository->findByRole($role);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('User with role %d not found.', $role));
        }

        return $user;
    }

    public function findByEmail(string $email): User
    {
        $user = $this->userRepository->findByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('User with email %d not found.', $email));
        }

        return $user;
    }
}
