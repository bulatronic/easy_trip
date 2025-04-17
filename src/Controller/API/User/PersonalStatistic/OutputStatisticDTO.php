<?php

namespace App\Controller\API\User\PersonalStatistic;

use App\Controller\DTO\OutputDTOInterface;

readonly class OutputStatisticDTO implements OutputDTOInterface
{
    public function __construct(
        public string $statisticsType,
        public string $periodType,
        public string $startDate,
        public string $endDate,
        public int $totalTrips,
        public string $totalRevenue,
        public int $totalPassengers,
        public string $averagePrice,
        public string $averagePassengers,
        public string $averageRating,
    ) {
    }
}
