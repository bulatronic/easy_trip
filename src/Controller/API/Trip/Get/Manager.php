<?php

namespace App\Controller\API\Trip\Get;

use App\Domain\Service\TripService;

readonly class Manager
{
    public function __construct(
        private TripService $service,
    ) {
    }

    public function get(int $id): OutputTripDTO
    {
        $user = $this->service->findTripById($id);

        return new OutputTripDTO(
            id: $user->getId(),
            driver_id: $user->getDriver()->getId(),
            start_location_id: $user->getStartLocation()->getId(),
            end_location_id: $user->getEndLocation()->getId(),
            departure_time: $user->getDepartureTime(),
            available_seats: $user->getAvailableSeats(),
            price_per_seat: $user->getPricePerSeat(),
            status: $user->getStatus(),
        );
    }
}
