<?php

namespace App\Controller\API\Location\Get;

use App\Domain\Service\LocationService;

readonly class Manager
{
    public function __construct(
        private LocationService $service,
    ) {
    }

    public function get(int $id): OutputLocationDTO
    {
        $location = $this->service->findLocationById($id);

        return new OutputLocationDTO(
            id: $location->getId(),
            name: $location->getName(),
            type: $location->getType(),
            latitude: $location->getLatitude(),
            longitude: $location->getLongitude(),
        );
    }
}
