<?php

namespace App\Domain\Service;

use App\Domain\Entity\Location;
use App\Domain\Model\Location\LocationModel;
use App\Domain\Repository\LocationRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class LocationService
{
    public function __construct(
        private LocationRepositoryInterface $locationRepository,
    ) {
    }

    public function create(LocationModel $model): Location
    {
        $location = new Location();
        $location->setName($model->name);
        $location->setType($model->type);
        $location->setLatitude($model->latitude);
        $location->setLongitude($model->longitude);
        $this->locationRepository->add($location);

        return $location;
    }

    public function update(int $id, LocationModel $model): Location
    {
        $location = $this->getLocationOrFail($id);
        $location->setName($model->name);
        $location->setType($model->type);
        $location->setLatitude($model->latitude);
        $location->setLongitude($model->longitude);
        $this->locationRepository->update($location);

        return $location;
    }

    public function remove(int $id): void
    {
        $location = $this->getLocationOrFail($id);
        $this->locationRepository->remove($location);
    }

    public function findLocationById(int $id): ?Location
    {
        return $this->getLocationOrFail($id);
    }

    private function getLocationOrFail(int $id): Location
    {
        $location = $this->locationRepository->findById($id);

        if (null === $location) {
            throw new NotFoundHttpException(sprintf('Location with id %d not found.', $id));
        }

        return $location;
    }
}
