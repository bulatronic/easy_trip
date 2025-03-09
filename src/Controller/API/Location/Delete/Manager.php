<?php

namespace App\Controller\API\Location\Delete;

use App\Domain\Service\LocationService;

readonly class Manager
{
    public function __construct(
        private LocationService $service,
    ) {
    }

    public function deleteLocation(int $id): void
    {
        $this->service->remove($id);
    }
}
