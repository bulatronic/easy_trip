<?php

namespace App\Domain\Model\Booking;

use App\Domain\Exception\ValidationException;

class CreateBookingModel
{
    use BookingModelTrait;

    public function __construct(
        public int $trip,
        public int $passenger,
        public int $seatsBooked,
        public string $status = 'booked',
    ) {
    }

    public function validate(): void
    {
        // 1. Проверка количества мест
        if ($this->seatsBooked > 7) {
            throw new ValidationException('Количество мест должно быть не более 7');
        }

        $trip = $this->getTrip();

        if ($trip->getAvailableSeats() < $this->seatsBooked) {
            throw new ValidationException('Недостаточно свободных мест');
        }

        if ($trip->getDepartureTime() < new \DateTime()) {
            throw new ValidationException('Нельзя бронировать места в уже начавшейся поездке');
        }

        if ('planned' !== $trip->getStatus()) {
            throw new ValidationException('Бронирование возможно только для поездок в статусе "planned"');
        }
    }
}
