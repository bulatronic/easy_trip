<?php

namespace App\Controller\API\User\Create;

use App\Controller\DTO\OutputDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class OutputUserDTO implements OutputDTOInterface
{
    public function __construct(
        #[Assert\NotNull]
        public int $id,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Length(min: 3, max: 10)]
        public string $username,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Email]
        public string $email,
    ) {
    }
}
