<?php

namespace App\Controller\API\Trip\Create;

use App\Domain\Model\Trip\CreateTripModel;
use App\Domain\Service\ModelFactory;
use App\Domain\Service\TripService;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<CreateTripModel> */
        private ModelFactory $modelFactory,
        private TripService $service,
    ) {
    }

    public function create(InputTripDTO $dto): OutputTripDTO
    {
        $createTripModel = $this->modelFactory->makeModel(
            CreateTripModel::class,
            $dto->driver_id,
            $dto->start_location_id,
            $dto->end_location_id,
            $dto->departure_time,
            $dto->available_seats,
            $dto->price_per_seat,
            $dto->status,
        );

        $trip = $this->service->create($createTripModel);

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
