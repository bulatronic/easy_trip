<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Statistics;

interface StatisticsRepositoryInterface
{
    public function findByPeriod(\DateTime $startDate, \DateTime $endDate, string $periodType): ?Statistics;

    public function findLatestByPeriodType(string $periodType): ?Statistics;
}
