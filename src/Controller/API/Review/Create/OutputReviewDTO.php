<?php

namespace App\Controller\API\Review\Create;

use App\Controller\DTO\OutputDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class OutputReviewDTO implements OutputDTOInterface
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\Positive]
        public int $id,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Positive]
        public int $user,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Positive]
        public int $trip,

        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Positive]
        #[Assert\Choice([1, 2, 3, 4, 5])]
        public int $rating,

        #[Assert\NotBlank(allowNull: true)]
        public ?string $comment,
    ) {
    }
}
