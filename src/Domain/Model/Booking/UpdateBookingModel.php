<?php

namespace App\Domain\Model\Booking;

use App\Domain\Entity\Booking;

class UpdateBookingModel
{
    use BookingModelTrait;

    public function __construct(
        public int $id,
        public ?int $trip,
        public ?int $passenger,
        public ?int $seats_booked,
        public ?string $status,
    ) {
    }

    public function updateBooking(Booking $booking): void
    {
        if (null !== $this->trip) {
            $booking->setTrip($this->getTrip());
        }
        if (null !== $this->passenger) {
            $booking->setPassenger($this->getPassenger());
        }
        if (null !== $this->seats_booked) {
            $booking->setSeatsBooked($this->seats_booked);
        }
        if (null !== $this->status) {
            $booking->setStatus($this->status);
        }
        $booking->setUpdatedAt();
    }
}
