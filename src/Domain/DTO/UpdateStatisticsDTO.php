<?php

namespace App\Domain\DTO;

class UpdateStatisticsDTO
{
    public function __construct(
        public string $periodType,
        public string $statisticsType,
        public string $startDate,
        public string $endDate,
        public ?int $driverId = null,
    ) {
    }
}
