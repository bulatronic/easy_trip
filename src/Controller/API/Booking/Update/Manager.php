<?php

namespace App\Controller\API\Booking\Update;

use App\Domain\Model\Booking\UpdateBookingModel;
use App\Domain\Service\BookingService;
use App\Domain\Service\ModelFactory;

readonly class Manager
{
    public function __construct(
        /** @var ModelFactory<UpdateBookingModel> */
        private ModelFactory $modelFactory,
        private BookingService $service,
    ) {
    }

    public function update(int $id, InputBookingDTO $dto): OutputBookingDTO
    {
        $updateBookingModel = $this->modelFactory->makeModel(
            UpdateBookingModel::class,
            $id,
            $dto->trip,
            $dto->passenger,
            $dto->seats_booked,
            $dto->status,
        );

        $booking = $this->service->update($id, $updateBookingModel);

        return new OutputBookingDTO(
            id: $booking->getId(),
            trip: $booking->getTrip()->getId(),
            passenger: $booking->getPassenger()->getId(),
            seats_booked: $booking->getSeatsBooked(),
            status: $booking->getStatus(),
        );
    }
}
