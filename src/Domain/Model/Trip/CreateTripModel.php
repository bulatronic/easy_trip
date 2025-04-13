<?php

namespace App\Domain\Model\Trip;

use App\Domain\Exception\ValidationException;

class CreateTripModel
{
    use TripModelTrait;

    public function __construct(
        public int $driver_id,
        public int $start_location_id,
        public int $end_location_id,
        public \DateTime $departure_time,
        public int $available_seats,
        public string $price_per_seat,
        public string $status = 'planned',
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->departure_time < new \DateTime()) {
            throw new ValidationException('Нельзя создать поездку в прошлом времени');
        }
    }
}
