<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Review;
use App\Domain\Entity\Trip;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Создает отзывы от участников поездки (водителей и пассажиров)
 * Включает рейтинг и комментарии
 * Отзывы создаются только для завершенных поездок.
 */
class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Получаем пользователей
        $passengers = $this->userRepository->findByRole(UserRole::ROLE_PASSENGER->value);
        if (empty($passengers)) {
            throw new \RuntimeException('Не найдены пассажиры в базе данных. Убедитесь, что UserFixtures загружены корректно.');
        }

        // Получаем поездки через EntityManager
        $trips = $manager->getRepository(Trip::class)->findAll();
        if (empty($trips)) {
            throw new \RuntimeException('Не найдены поездки в базе данных. Убедитесь, что TripFixtures загружены корректно.');
        }

        $reviews = [
            [
                'user' => $passengers[0],
                'trip' => $trips[0], // Уфа -> Стерлитамак
                'rating' => 5,
                'comment' => 'Отличная поездка, водитель вежливый, машина комфортная.',
            ],
            [
                'user' => $passengers[1] ?? $passengers[0],
                'trip' => $trips[0], // Уфа -> Стерлитамак
                'rating' => 4,
                'comment' => 'Хорошая поездка, но немного задержались в пути.',
            ],
            [
                'user' => $passengers[0],
                'trip' => $trips[2], // Салават -> Октябрьский
                'rating' => 5,
                'comment' => 'Отличный сервис, рекомендую!',
            ],
        ];

        foreach ($reviews as $reviewData) {
            $review = new Review();
            $review->setUser($reviewData['user']);
            $review->setTrip($reviewData['trip']);
            $review->setRating($reviewData['rating']);
            $review->setComment($reviewData['comment']);
            $review->setCreatedAt();

            $manager->persist($review);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TripFixtures::class,
            BookingFixtures::class,
        ];
    }
}
