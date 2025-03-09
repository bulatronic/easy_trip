<?php

namespace App\Controller\API\User\Update;

use App\Domain\Model\User\UpdateUserModel;
use App\Domain\Service\ModelFactory;
use App\Domain\Service\UserService;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<UpdateUserModel> */
        private ModelFactory $modelFactory,
        private UserService $service,
    ) {
    }

    public function update(int $id, InputUserDTO $dto): OutputUserDTO
    {
        $updateUserModel = $this->modelFactory->makeModel(
            UpdateUserModel::class,
            $dto->username,
            $dto->email,
            $dto->password,
            $dto->role,
            $id,
        );

        $user = $this->service->update($id, $updateUserModel);

        return new OutputUserDTO(
            id: $user->getId(),
            username: $user->getUsername(),
            email: $user->getEmail(),
            role: $user->getRole(),
        );
    }
}
