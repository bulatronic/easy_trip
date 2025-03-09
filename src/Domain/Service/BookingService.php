<?php

namespace App\Domain\Service;

use App\Domain\Entity\Booking;
use App\Domain\Model\Booking\CreateBookingModel;
use App\Domain\Model\Booking\UpdateBookingModel;
use App\Domain\Repository\BookingRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class BookingService
{
    public function __construct(
        private BookingRepositoryInterface $bookingRepository,
        private UserService $userService,
        private TripService $tripService,
    ) {
    }

    public function create(CreateBookingModel $model): Booking
    {
        $model->setServices($this->userService, $this->tripService);

        $booking = new Booking();
        $booking->setTrip($model->getTrip());
        $booking->setPassenger($model->getPassenger());
        $booking->setSeatsBooked($model->seatsBooked);
        $booking->setStatus($model->status);
        $booking->setCreatedAt();

        $this->bookingRepository->add($booking);

        return $booking;
    }

    public function remove(int $id): void
    {
        $booking = $this->getBookingOrFail($id);
        $this->bookingRepository->remove($booking);
    }

    public function update(int $id, UpdateBookingModel $model): Booking
    {
        $model->setServices($this->userService, $this->tripService);
        $booking = $this->getBookingOrFail($id);
        $model->updateBooking($booking);

        $this->bookingRepository->update($booking);

        return $booking;
    }

    public function findBookingById(int $id): ?Booking
    {
        return $this->getBookingOrFail($id);
    }

    private function getBookingOrFail(int $id): Booking
    {
        $booking = $this->bookingRepository->findById($id);

        if (null === $booking) {
            throw new NotFoundHttpException(sprintf('Booking with id %d not found.', $id));
        }

        return $booking;
    }
}
