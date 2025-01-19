<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

/**
 * @template T
 */
abstract class AbstractRepository
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    protected function flush(): void
    {
        $this->entityManager->flush();
    }

    protected function store(EntityInterface $entity): int
    {
        $this->entityManager->persist($entity);
        $this->flush();

        return $entity->getId();
    }

    /**
     * @throws ORMException
     */
    public function refresh(EntityInterface $entity): void
    {
        $this->entityManager->refresh($entity);
    }
}
