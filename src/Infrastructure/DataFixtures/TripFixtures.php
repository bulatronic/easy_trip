<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Trip;
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Создает поездки между городами Башкортостана
 * Устанавливает водителей, доступные места и цены
 * Все поездки в статусе "scheduled".
 */
class TripFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly LocationRepositoryInterface $locationRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Получаем водителей
        $drivers = $this->userRepository->findByRole(UserRole::ROLE_DRIVER->value);
        if (empty($drivers)) {
            throw new \RuntimeException('Не найдены водители в базе данных. Убедитесь, что UserFixtures загружены корректно.');
        }

        // Получаем города
        $ufa = $this->locationRepository->findByName('Уфа');
        $sterlitamak = $this->locationRepository->findByName('Стерлитамак');
        $salavat = $this->locationRepository->findByName('Салават');
        $oktyabrsky = $this->locationRepository->findByName('Октябрьский');

        if (!$ufa || !$sterlitamak || !$salavat || !$oktyabrsky) {
            throw new \RuntimeException('Не найдены все необходимые города в базе данных. Убедитесь, что LocationFixtures загружены корректно.');
        }

        $trips = [
            [
                'driver' => $drivers[0],
                'startLocation' => $ufa,
                'endLocation' => $sterlitamak,
                'departureTime' => new \DateTime('+1 day 10:00'),
                'availableSeats' => 3,
                'pricePerSeat' => 500,
                'status' => 'planned',
            ],
            [
                'driver' => $drivers[0],
                'startLocation' => $sterlitamak,
                'endLocation' => $ufa,
                'departureTime' => new \DateTime('+2 days 15:00'),
                'availableSeats' => 2,
                'pricePerSeat' => 500,
                'status' => 'planned',
            ],
            [
                'driver' => $drivers[1] ?? $drivers[0],
                'startLocation' => $salavat,
                'endLocation' => $oktyabrsky,
                'departureTime' => new \DateTime('+3 days 08:00'),
                'availableSeats' => 4,
                'pricePerSeat' => 300,
                'status' => 'planned',
            ],
            [
                'driver' => $drivers[1] ?? $drivers[0],
                'startLocation' => $oktyabrsky,
                'endLocation' => $ufa,
                'departureTime' => new \DateTime('+4 days 12:00'),
                'availableSeats' => 3,
                'pricePerSeat' => 400,
                'status' => 'planned',
            ],
        ];

        foreach ($trips as $tripData) {
            $trip = new Trip();
            $trip->setDriver($tripData['driver']);
            $trip->setStartLocation($tripData['startLocation']);
            $trip->setEndLocation($tripData['endLocation']);
            $trip->setDepartureTime($tripData['departureTime']);
            $trip->setAvailableSeats($tripData['availableSeats']);
            $trip->setPricePerSeat($tripData['pricePerSeat']);
            $trip->setStatus($tripData['status']);
            $trip->setCreatedAt();

            $manager->persist($trip);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            LocationFixtures::class,
        ];
    }
}
