<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Statistics;
use App\Domain\Repository\StatisticsRepositoryInterface;

class StatisticsRepository extends AbstractRepository implements StatisticsRepositoryInterface
{
    public function add(Statistics $statistics): int
    {
        return $this->save($statistics);
    }

    public function findByPeriod(\DateTime $startDate, \DateTime $endDate, string $periodType): ?Statistics
    {
        return $this->em->getRepository(Statistics::class)->findOneBy([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodType' => $periodType,
        ]);
    }

    public function findLatestByPeriodType(string $periodType): ?Statistics
    {
        return $this->em->getRepository(Statistics::class)->findOneBy(
            ['periodType' => $periodType],
            ['createdAt' => 'DESC']
        );
    }
}
