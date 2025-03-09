<?php

namespace App\Controller\API\Booking\Get;

use App\Domain\Service\BookingService;

readonly class Manager
{
    public function __construct(
        private BookingService $service,
    ) {
    }

    public function get(int $id): OutputBookingDTO
    {
        $booking = $this->service->findBookingById($id);

        return new OutputBookingDTO(
            id: $booking->getId(),
            trip: $booking->getTrip()->getId(),
            passenger: $booking->getPassenger()->getId(),
            seats_booked: $booking->getSeatsBooked(),
            status: $booking->getStatus(),
        );
    }
}
