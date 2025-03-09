<?php

namespace App\Controller\API\Booking\Get;

use App\Controller\DTO\OutputDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class OutputBookingDTO implements OutputDTOInterface
{
    public function __construct(
        #[Assert\NotNull]
        public int $id,
        #[Assert\NotBlank]
        #[Assert\NotNull]
        public int $trip,
        #[Assert\NotBlank]
        #[Assert\NotNull]
        public int $passenger,
        #[Assert\NotBlank]
        #[Assert\NotNull]
        public int $seats_booked,
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Choice(['booked', 'cancelled', 'completed'])]
        public string $status,
    ) {
    }
}
