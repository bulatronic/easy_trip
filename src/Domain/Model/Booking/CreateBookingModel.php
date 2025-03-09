<?php

namespace App\Domain\Model\Booking;

class CreateBookingModel
{
    use BookingModelTrait;

    public function __construct(
        public int $trip,
        public int $passenger,
        public int $seatsBooked,
        public string $status,
    ) {
    }
}
