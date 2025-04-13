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
        $model->validate();
        $trip = $model->getTrip();

        $booking = new Booking();
        $booking->setTrip($trip);
        $booking->setPassenger($model->getPassenger());
        $booking->setSeatsBooked($model->seatsBooked);
        $booking->setStatus($model->status);
        $booking->setCreatedAt();

        // Обновляем количество доступных мест
        $this->tripService->updateAvailableSeats(
            $trip->getId(),
            $trip->getAvailableSeats() - $model->seatsBooked
        );

        $this->bookingRepository->add($booking);

        return $booking;
    }

    public function remove(int $id): void
    {
        $booking = $this->getBookingOrFail($id);

        // Возвращаем места в поездку
        $trip = $booking->getTrip();
        $this->tripService->updateAvailableSeats(
            $trip->getId(),
            $trip->getAvailableSeats() + $booking->getSeatsBooked()
        );

        $this->bookingRepository->remove($booking);
    }

    public function update(int $id, UpdateBookingModel $model): Booking
    {
        $model->setServices($this->userService, $this->tripService);
        $booking = $this->getBookingOrFail($id);
        // Сохраняем старые значения для расчета изменений
        $oldSeats = $booking->getSeatsBooked();
        $oldTripId = $booking->getTrip()->getId();

        // Валидация перед изменениями
        $model->validate($booking);

        // Применяем изменения
        $model->updateBooking($booking);

        // Обрабатываем изменение количества мест
        if (null !== $model->seats_booked || null !== $model->trip) {
            $this->handleSeatsUpdate($booking, $oldSeats, $oldTripId);
        }

        $this->bookingRepository->update($booking);

        return $booking;
    }

    private function handleSeatsUpdate(Booking $booking, int $oldSeats, int $oldTripId): void
    {
        $newSeats = $booking->getSeatsBooked();
        $newTripId = $booking->getTrip()->getId();

        // Если изменилась поездка
        if ($oldTripId !== $newTripId) {
            // Возвращаем места в старую поездку
            $this->tripService->updateAvailableSeats(
                $oldTripId,
                $this->tripService->findTripById($oldTripId)->getAvailableSeats() + $oldSeats
            );

            // Забираем места из новой поездки
            $this->tripService->updateAvailableSeats(
                $newTripId,
                $booking->getTrip()->getAvailableSeats() - $newSeats
            );
        }
        // Если изменилось только количество мест
        elseif ($oldSeats !== $newSeats) {
            $seatsDifference = $oldSeats - $newSeats;
            $this->tripService->updateAvailableSeats(
                $newTripId,
                $booking->getTrip()->getAvailableSeats() + $seatsDifference
            );
        }
    }

    public function findBookingById(int $id): ?Booking
    {
        return $this->getBookingOrFail($id);
    }

    public function completeBooking(int $id): void
    {
        $booking = $this->getBookingOrFail($id);
        $booking->setStatus('completed');
        $this->bookingRepository->update($booking);
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
