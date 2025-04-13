<?php

namespace App\Controller\API\Booking\Create;

use Symfony\Component\Validator\Constraints as Assert;

class InputBookingDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\NotNull]
        public int $trip,
        #[Assert\NotBlank]
        #[Assert\NotNull]
        public int $passenger,
        #[Assert\NotBlank]
        #[Assert\NotNull]
        #[Assert\Positive]
        public int $seats_booked,
    ) {
    }
}
