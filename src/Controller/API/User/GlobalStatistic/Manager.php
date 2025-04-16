<?php

namespace App\Controller\API\User\GlobalStatistic;

use App\Domain\Service\StatisticsService;

readonly class Manager
{
    public function __construct(
        private StatisticsService $statisticsService,
    ) {
    }

    public function get(InputStatisticDTO $dto): OutputStatisticDTO
    {
        $statistic = $this->statisticsService->getGlobalStatistics($dto->periodType);

        return new OutputStatisticDTO(
            statisticsType: 'global',
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
