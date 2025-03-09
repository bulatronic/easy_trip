<?php

namespace App\Controller\API\User\Create;

use Symfony\Component\Validator\Constraints as Assert;

class InputUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Length(min: 3, max: 10)]
        public string $username,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Email]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Length(min: 6)]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Choice(['admin', 'driver', 'passenger'])]
        public string $role,
    ) {
    }
}
