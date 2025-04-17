<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Statistics;
use App\Domain\Entity\User;
use App\Domain\Repository\StatisticsRepositoryInterface;

class StatisticsRepository extends AbstractRepository implements StatisticsRepositoryInterface
{
    public function add(Statistics $statistics): int
    {
        return $this->save($statistics);
    }

    public function findDriverStatisticsByPeriod(User $driver, \DateTimeInterface $startDate, \DateTimeInterface $endDate, string $periodType): ?Statistics
    {
        return $this->em->createQueryBuilder()
            ->select('s')
            ->from(Statistics::class, 's')
            ->where('s.driver = :driver')
            ->andWhere('s.periodType = :periodType')
            ->andWhere('s.startDate >= :startDate')
            ->andWhere('s.endDate <= :endDate')
            ->setMaxResults(1)
            ->setParameter('driver', $driver)
            ->setParameter('periodType', $periodType)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findStatisticByPeriodType(\DateTimeInterface $startDate, \DateTimeInterface $endDate, string $periodType): ?Statistics
    {
        return $this->em->createQueryBuilder()
            ->select('s')
            ->from(Statistics::class, 's')
            ->where('s.driver IS NULL')
            ->andWhere('s.statisticsType = :statisticsType')
            ->andWhere('s.periodType = :periodType')
            ->andWhere('s.startDate >= :startDate')
            ->andWhere('s.endDate <= :endDate')
            ->orderBy('s.createdAt', 'DESC')
            ->setMaxResults(1)
            ->setParameter('statisticsType', Statistics::TYPE_GLOBAL)
            ->setParameter('periodType', $periodType)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
