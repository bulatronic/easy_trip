<?php

namespace App\Controller\API\User\Update;

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
        #[Assert\Choice(['admin', 'driver', 'passenger'])]
        public ?string $role,
    ) {
    }
}
