<?php

namespace App\Controller\API\Booking\Delete;

use App\Domain\Service\BookingService;

readonly class Manager
{
    public function __construct(
        private BookingService $service,
    ) {
    }

    public function deleteBooking(int $id): void
    {
        $this->service->remove($id);
    }
}
