<?php

namespace App\Domain\Model\Booking;

use App\Domain\Entity\Booking;
use App\Domain\Exception\ValidationException;

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

    public function validate(Booking $originalBooking): void
    {
        if (null !== $this->seats_booked) {
            if ($this->seats_booked <= 0) {
                throw new ValidationException('Количество мест должно быть положительным');
            }

            // Проверяем доступность мест только если меняем их количество
            if ($this->seats_booked != $originalBooking->getSeatsBooked()) {
                $trip = $this->trip ? $this->getTrip() : $originalBooking->getTrip();
                $availableSeats = $trip->getAvailableSeats() + $originalBooking->getSeatsBooked();

                if ($this->seats_booked > $availableSeats) {
                    throw new ValidationException('Недостаточно свободных мест');
                }
            }
        }
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
