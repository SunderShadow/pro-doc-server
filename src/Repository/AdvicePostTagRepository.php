<?php

namespace App\Repository;

use App\Entity\AdvicePostTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdvicePostTag>
 */
class AdvicePostTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvicePostTag::class);
    }

    /**
     * @param array $names
     * @return AdvicePostTag[]
     */
    public function findManyByNames(array $names): array
    {
        return $this->createQueryBuilder('x')
            ->select()
            ->where('x.title IN (:names)')
            ->setParameter('names', $names)
            ->getQuery()
            ->getResult();
    }
}
