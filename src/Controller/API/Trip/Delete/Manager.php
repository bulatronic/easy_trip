<?php

namespace App\Controller\API\Trip\Delete;

use App\Domain\Service\TripService;

readonly class Manager
{
    public function __construct(
        private TripService $service,
    ) {
    }

    public function deleteTrip(int $id): void
    {
        $this->service->remove($id);
    }
}
