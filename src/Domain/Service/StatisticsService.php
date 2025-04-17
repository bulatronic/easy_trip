<?php

namespace App\Domain\Service;

use App\Domain\Bus\UpdateStatisticsInterface;
use App\Domain\DTO\UpdateStatisticsDTO;
use App\Domain\Entity\Statistics;
use App\Domain\Entity\User;
use App\Domain\Repository\ReviewRepositoryInterface;
use App\Domain\Repository\StatisticsRepositoryInterface;
use App\Domain\Repository\TripRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class StatisticsService
{
    public function __construct(
        private StatisticsRepositoryInterface $statisticsRepository,
        private TripRepositoryInterface $tripRepository,
        private ReviewRepositoryInterface $reviewRepository,
        private LoggerInterface $logger,
        private UpdateStatisticsInterface $updateStatisticsBus,
    ) {
    }

    public function generatePersonalStatistics(User $driver, string $periodType, \DateTime $startDate, \DateTime $endDate): void
    {
        $endDate = $this->normalizeEndDate($endDate);
        $trips = $this->tripRepository->findByDriverAndDateRange($driver, $startDate, $endDate);
        $statistics = $this->calculateStatistics($trips);

        $statisticsEntity = $this->createStatisticsEntity(
            $driver,
            Statistics::TYPE_PERSONAL,
            $periodType,
            $startDate,
            $endDate,
            $statistics
        );

        $this->statisticsRepository->add($statisticsEntity);
    }

    public function generateGlobalStatistics(string $periodType, \DateTime $startDate, \DateTime $endDate): void
    {
        $endDate = $this->normalizeEndDate($endDate);
        $trips = $this->tripRepository->findByDateRange($startDate, $endDate);
        $statistics = $this->calculateStatistics($trips);

        $statisticsEntity = $this->createStatisticsEntity(
            null,
            Statistics::TYPE_GLOBAL,
            $periodType,
            $startDate,
            $endDate,
            $statistics
        );

        $this->statisticsRepository->add($statisticsEntity);
    }

    private function calculateStatistics(array $trips): array
    {
        $totalTrips = count($trips);
        $totalRevenue = 0;
        $totalPassengers = 0;
        $totalRating = 0;
        $ratedTrips = 0;

        // Извлекаем все ID поездок
        $tripIds = array_map(function ($trip) {
            return $trip->getId();
        }, $trips);

        // Предзагрузка всех отзывов для всех поездок одним запросом
        $reviewsByTripId = [];
        if (!empty($tripIds)) {
            try {
                $allReviews = $this->reviewRepository->findByTripIds($tripIds);

                // Группируем отзывы по ID поездки
                foreach ($allReviews as $review) {
                    $tripId = $review->getTripId();
                    if (!isset($reviewsByTripId[$tripId])) {
                        $reviewsByTripId[$tripId] = [];
                    }
                    $reviewsByTripId[$tripId][] = $review;
                }
            } catch (\Throwable $e) {
                $this->logger->error('Failed to fetch reviews for trips', [
                    'error' => $e->getMessage(),
                    'trip_ids' => $tripIds,
                ]);
                // Продолжаем выполнение без отзывов
            }
        }

        // Обрабатываем каждую поездку
        foreach ($trips as $trip) {
            $tripId = $trip->getId();
            $totalRevenue += $trip->getPricePerSeat() * $trip->getAvailableSeats();
            $totalPassengers += $trip->getAvailableSeats();

            // Используем предзагруженные отзывы
            if (isset($reviewsByTripId[$tripId])) {
                foreach ($reviewsByTripId[$tripId] as $review) {
                    $rating = $review->getRating();
                    if (null !== $rating) {
                        $totalRating += $rating;
                        ++$ratedTrips;
                    }
                }
            }
        }

        // Рассчет средних значений
        $averagePrice = $totalTrips > 0 ? (string) ($totalRevenue / $totalTrips) : '0.00';
        $averagePassengers = $totalTrips > 0 ? (string) ($totalPassengers / $totalTrips) : '0.00';
        $averageRating = $ratedTrips > 0 ? (string) ($totalRating / $ratedTrips) : '0.00';

        return [
            'totalTrips' => $totalTrips,
            'totalRevenue' => (string) $totalRevenue,
            'totalPassengers' => $totalPassengers,
            'averagePrice' => $averagePrice,
            'averagePassengers' => $averagePassengers,
            'averageRating' => $averageRating,
        ];
    }

    public function updateStatistics(UpdateStatisticsDTO $updateStatisticsDTO): bool
    {
        return $this->updateStatisticsBus->sendUpdateStatisticsMessage($updateStatisticsDTO);
    }

    public function getDriverStatistics(User $driver, string $periodType): Statistics
    {
        [$startDate, $endDate] = $this->getDefaultDates($periodType);

        $statistic = $this->statisticsRepository->findDriverStatisticsByPeriod(
            $driver,
            $startDate,
            $endDate,
            $periodType
        );

        if (!$statistic) {
            throw new NotFoundHttpException(sprintf('Statistics with id %d not found.', $driver->getId()));
        }

        return $statistic;
    }

    public function getGlobalStatistics(string $periodType): ?Statistics
    {
        [$startDate, $endDate] = $this->getDefaultDates($periodType);

        $statistic = $this->statisticsRepository->findStatisticByPeriodType(
            $startDate,
            $endDate,
            $periodType
        );

        if (!$statistic) {
            throw new NotFoundHttpException('Entry not found.');
        }

        return $statistic;
    }

    private function normalizeEndDate(\DateTime $endDate): \DateTime
    {
        $normalizedEndDate = clone $endDate;
        $normalizedEndDate->setTime(23, 59, 59);

        return $normalizedEndDate;
    }

    private function createStatisticsEntity(
        ?User $driver,
        string $statisticsType,
        string $periodType,
        \DateTime $startDate,
        \DateTime $endDate,
        array $statistics,
    ): Statistics {
        $statisticsEntity = new Statistics();

        if (null !== $driver) {
            $statisticsEntity->setDriver($driver);
        }

        $statisticsEntity->setStatisticsType($statisticsType);
        $statisticsEntity->setPeriodType($periodType);
        $statisticsEntity->setStartDate($startDate);
        $statisticsEntity->setEndDate($endDate);
        $statisticsEntity->setTotalTrips($statistics['totalTrips']);
        $statisticsEntity->setTotalRevenue($statistics['totalRevenue']);
        $statisticsEntity->setTotalPassengers($statistics['totalPassengers']);
        $statisticsEntity->setAveragePrice($statistics['averagePrice']);
        $statisticsEntity->setAveragePassengers($statistics['averagePassengers']);
        $statisticsEntity->setAverageRating($statistics['averageRating']);

        return $statisticsEntity;
    }

    private function getDefaultDates(string $period): array
    {
        $now = new \DateTimeImmutable();

        return match ($period) {
            'daily' => [
                $now->setTime(0, 0),
                $now->setTime(23, 59, 59),
            ],
            'weekly' => [
                $now->modify('monday this week')->setTime(0, 0),
                $now->modify('sunday this week')->setTime(23, 59, 59),
            ],
            'monthly' => [
                $now->modify('first day of this month')->setTime(0, 0),
                $now->modify('last day of this month')->setTime(23, 59, 59),
            ],
            'yearly' => [
                $now->modify('first day of january')->setTime(0, 0),
                $now->modify('last day of december')->setTime(23, 59, 59),
            ],
        };
    }
}
