<?php

namespace App\Controller\CLI;

use App\Controller\CLI\Input\UpdateStatisticsDTO;
use App\Domain\DTO\UpdateStatisticsDTO as InputUpdateStatisticsDTO;
use App\Domain\Service\StatisticsService;

readonly class Manager
{
    public function __construct(
        private StatisticsService $statisticsService,
    ) {
    }

    public function updateStatistics(UpdateStatisticsDTO $dto): bool
    {
        return $this->statisticsService->updateStatistics(
            new InputUpdateStatisticsDTO(
                $dto->periodType,
                $dto->statisticsType,
                $dto->startDate->format('Y-m-d H:i:s'),
                $dto->endDate->format('Y-m-d H:i:s'),
                'personal' === $dto->statisticsType ? (int) $dto->driverId : null,
            )
        );
    }
}
