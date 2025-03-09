<?php

namespace App\Domain\Service;

use App\Domain\Entity\Trip;
use App\Domain\Model\Trip\CreateTripModel;
use App\Domain\Model\Trip\UpdateTripModel;
use App\Domain\Repository\TripRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class TripService
{
    public function __construct(
        private TripRepositoryInterface $tripRepository,
        private LocationService $locationService,
        private UserService $userService,
    ) {
    }

    public function create(CreateTripModel $model): Trip
    {
        $model->setServices($this->userService, $this->locationService);

        $trip = new Trip();
        $trip->setDriver($model->getDriver());
        $trip->setStartLocation($model->getStartLocation());
        $trip->setEndLocation($model->getEndLocation());
        $trip->setDepartureTime($model->departure_time);
        $trip->setAvailableSeats($model->available_seats);
        $trip->setPricePerSeat($model->price_per_seat);
        $trip->setStatus($model->status);
        $trip->setCreatedAt();

        $this->tripRepository->add($trip);

        return $trip;
    }

    public function update(int $id, UpdateTripModel $model): Trip
    {
        $model->setServices($this->userService, $this->locationService);

        $trip = $this->getTripOrFail($id);
        $trip->setDriver($model->getDriver());
        $trip->setStartLocation($model->getStartLocation());
        $trip->setEndLocation($model->getEndLocation());
        $trip->setDepartureTime($model->departure_time);
        $trip->setAvailableSeats($model->available_seats);
        $trip->setPricePerSeat($model->price_per_seat);
        $trip->setStatus($model->status);
        $this->tripRepository->update($trip);

        return $trip;
    }

    public function remove(int $id): void
    {
        $user = $this->getTripOrFail($id);
        $this->tripRepository->remove($user);
    }

    public function findTripById(int $id): ?Trip
    {
        return $this->getTripOrFail($id);
    }

    private function getTripOrFail(int $id): Trip
    {
        $trip = $this->tripRepository->findById($id);

        if (null === $trip) {
            throw new NotFoundHttpException(sprintf('Trip with id %d not found.', $id));
        }

        return $trip;
    }
}
