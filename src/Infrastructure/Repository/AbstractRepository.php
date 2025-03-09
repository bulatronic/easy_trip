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
        protected EntityManagerInterface $em,
    ) {
    }

    protected function flush(): void
    {
        $this->em->flush();
    }

    protected function save(EntityInterface $entity): int
    {
        $this->em->persist($entity);
        $this->flush();

        return $entity->getId();
    }

    protected function delete(EntityInterface $entity): void
    {
        $this->em->remove($entity);
        $this->flush();
    }

    /**
     * @throws ORMException
     */
    protected function refresh(EntityInterface $entity): void
    {
        $this->em->refresh($entity);
    }
}
