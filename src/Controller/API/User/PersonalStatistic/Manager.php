<?php

namespace App\Controller\API\User\PersonalStatistic;

use App\Domain\Service\StatisticsService;
use App\Domain\Service\UserService;

readonly class Manager
{
    public function __construct(
        private StatisticsService $statisticsService,
        private UserService $userService,
    ) {
    }

    public function get(InputStatisticDTO $dto): OutputStatisticDTO
    {
        $user = $this->userService->findUserById($dto->driverId);
        $statistic = $this->statisticsService->getDriverStatistics($user, $dto->periodType);

        return new OutputStatisticDTO(
            statisticsType: 'personal',
            periodType: $dto->periodType,
            startDate: $statistic->getStartDate()->format('Y-m-d H:i:s'),
            endDate: $statistic->getEndDate()->format('Y-m-d H:i:s'),
            totalTrips: $statistic->getTotalTrips(),
            totalRevenue: $statistic->getTotalRevenue(),
            totalPassengers: $statistic->getTotalPassengers(),
            averagePrice: $statistic->getAveragePrice(),
            averagePassengers: $statistic->getAveragePassengers(),
            averageRating: $statistic->getAverageRating(),
        );
    }
}
