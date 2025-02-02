<?php

namespace App\Controller\API\User\Create;

use App\Domain\Model\User\CreateUserModel;
use App\Domain\Service\ModelFactory;
use App\Domain\Service\UserService;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<CreateUserModel> */
        private ModelFactory $modelFactory,
        private UserService $service,
    ) {
    }

    public function create(InputUserDTO $dto): OutputUserDTO
    {
        $createUserModel = $this->modelFactory->makeModel(
            CreateUserModel::class,
            $dto->username,
            $dto->email,
            $dto->password,
            $dto->role,
        );

        $user = $this->service->create($createUserModel);

        return new OutputUserDTO(
            id: $user->getId(),
            username: $user->getUsername(),
            email: $user->getEmail(),
            role: $user->getRole(),
        );
    }
}
