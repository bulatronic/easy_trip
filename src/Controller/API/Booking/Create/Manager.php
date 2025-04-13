<?php

namespace App\Controller\API\Booking\Create;

use App\Domain\Model\Booking\CreateBookingModel;
use App\Domain\Service\BookingService;
use App\Domain\Service\ModelFactory;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<CreateBookingModel> */
        private ModelFactory $modelFactory,
        private BookingService $service,
    ) {
    }

    public function create(InputBookingDTO $dto): OutputBookingDTO
    {
        $createBookingModel = $this->modelFactory->makeModel(
            CreateBookingModel::class,
            $dto->trip,
            $dto->passenger,
            $dto->seats_booked,
        );

        $booking = $this->service->create($createBookingModel);

        return new OutputBookingDTO(
            id: $booking->getId(),
            trip: $booking->getTrip()->getId(),
            passenger: $booking->getPassenger()->getId(),
            seats_booked: $booking->getSeatsBooked(),
            status: $booking->getStatus(),
        );
    }
}
