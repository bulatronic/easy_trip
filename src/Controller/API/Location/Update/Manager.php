<?php

namespace App\Controller\API\Location\Update;

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

    public function update(int $id, InputLocationDTO $dto): OutputLocationDTO
    {
        $locationModel = $this->modelFactory->makeModel(
            LocationModel::class,
            $dto->name,
            $dto->type,
            $dto->latitude,
            $dto->longitude,
            $id,
        );

        $location = $this->service->update($id, $locationModel);

        return new OutputLocationDTO(
            id: $location->getId(),
            name: $location->getName(),
            type: $location->getType(),
            latitude: $location->getLatitude(),
            longitude: $location->getLongitude(),
        );
    }
}
