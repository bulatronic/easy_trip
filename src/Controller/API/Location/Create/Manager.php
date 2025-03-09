<?php

namespace App\Controller\API\Location\Create;

use App\Domain\Model\Location\LocationModel;
use App\Domain\Service\LocationService;
use App\Domain\Service\ModelFactory;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<LocationModel> */
        private ModelFactory $modelFactory,
        private LocationService $service,
    ) {
    }

    public function create(InputLocationDTO $dto): OutputLocationDTO
    {
        $locationModel = $this->modelFactory->makeModel(
            LocationModel::class,
            $dto->name,
            $dto->type,
            $dto->latitude,
            $dto->longitude,
        );

        $location = $this->service->create($locationModel);

        return new OutputLocationDTO(
            id: $location->getId(),
            name: $location->getName(),
            type: $location->getType(),
            latitude: $location->getLatitude(),
            longitude: $location->getLongitude(),
        );
    }
}
