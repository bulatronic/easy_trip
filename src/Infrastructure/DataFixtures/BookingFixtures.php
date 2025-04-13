<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Booking;
use App\Domain\Entity\Trip;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Создает бронирования только для пассажиров
 * Обновляет количество доступных мест в поездках
 * Все бронирования в статусе "booked".
 */
class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Получаем пассажиров
        $passengers = $this->userRepository->findByRole(UserRole::ROLE_PASSENGER->value);
        if (empty($passengers)) {
            throw new \RuntimeException('Не найдены пассажиры в базе данных. Убедитесь, что UserFixtures загружены корректно.');
        }

        // Получаем поездки
        $trips = $manager->getRepository(Trip::class)->findAll();
        if (empty($trips)) {
            throw new \RuntimeException('Не найдены поездки в базе данных. Убедитесь, что TripFixtures загружены корректно.');
        }

        $bookings = [
            [
                'trip' => $trips[0], // Уфа -> Стерлитамак
                'passenger' => $passengers[0],
                'seatsBooked' => 1,
                'status' => 'booked',
            ],
            [
                'trip' => $trips[0], // Уфа -> Стерлитамак
                'passenger' => $passengers[1] ?? $passengers[0],
                'seatsBooked' => 2,
                'status' => 'booked',
            ],
            [
                'trip' => $trips[2], // Салават -> Октябрьский
                'passenger' => $passengers[0],
                'seatsBooked' => 1,
                'status' => 'booked',
            ],
        ];

        foreach ($bookings as $bookingData) {
            $booking = new Booking();
            $booking->setTrip($bookingData['trip']);
            $booking->setPassenger($bookingData['passenger']);
            $booking->setSeatsBooked($bookingData['seatsBooked']);
            $booking->setStatus($bookingData['status']);
            $booking->setCreatedAt();

            $manager->persist($booking);

            // Обновляем количество доступных мест в поездке
            $trip = $bookingData['trip'];
            $trip->setAvailableSeats($trip->getAvailableSeats() - $bookingData['seatsBooked']);
            $manager->persist($trip);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TripFixtures::class,
        ];
    }
}
