<?php

namespace App\Controller\API\Trip\Update;

use App\Domain\Model\Trip\UpdateTripModel;
use App\Domain\Service\ModelFactory;
use App\Domain\Service\TripService;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<UpdateTripModel> */
        private ModelFactory $modelFactory,
        private TripService $service,
    ) {
    }

    public function update(int $id, InputTripDTO $dto): OutputTripDTO
    {
        $updateTripModel = $this->modelFactory->makeModel(
            UpdateTripModel::class,
            $dto->driver_id,
            $dto->start_location_id,
            $dto->end_location_id,
            $dto->departure_time,
            $dto->available_seats,
            $dto->price_per_seat,
            $dto->status,
            $id,
        );

        $trip = $this->service->update($id, $updateTripModel);

        return new OutputTripDTO(
            $trip->getId(),
            $trip->getDriver()->getId(),
            $trip->getStartLocation()->getId(),
            $trip->getEndLocation()->getId(),
            $trip->getDepartureTime(),
            $trip->getAvailableSeats(),
            $trip->getPricePerSeat(),
            $trip->getStatus(),
        );
    }
}
