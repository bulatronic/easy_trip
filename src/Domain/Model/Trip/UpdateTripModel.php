<?php

namespace App\Domain\Model\Trip;

class UpdateTripModel
{
    use TripModelTrait;

    public function __construct(
        public int $driver_id,
        public int $start_location_id,
        public int $end_location_id,
        public \DateTime $departure_time,
        public int $available_seats,
        public string $price_per_seat,
        public string $status,
        public int $id,
    ) {
    }
}
