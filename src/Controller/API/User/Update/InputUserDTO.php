<?php

namespace App\Controller\API\User\Update;

use App\Domain\ValueObject\UserRole;
use Symfony\Component\Validator\Constraints as Assert;

class InputUserDTO
{
    public function __construct(
        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min: 3, max: 10)]
        public ?string $username,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Email]
        public ?string $email,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Length(min: 6)]
        public ?string $password,

        #[Assert\NotBlank(allowNull: true)]
        #[Assert\Choice([
            UserRole::ROLE_PASSENGER->value,
            UserRole::ROLE_DRIVER->value,
        ])]
        public ?string $role,
    ) {
    }
}
