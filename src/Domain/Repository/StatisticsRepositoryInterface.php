<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Statistics;
use App\Domain\Entity\User;

interface StatisticsRepositoryInterface
{
    public function findDriverStatisticsByPeriod(User $driver, \DateTime $startDate, \DateTime $endDate, string $periodType): ?Statistics;

    public function findStatisticByPeriodType(\DateTime $startDate, \DateTime $endDate, string $periodType): ?Statistics;
}
