<?php

namespace App\Repository;

use App\Contracts\Library\Advice\PostStorage;
use App\Entity\AdvicePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdvicePost>
 */
class AdvicePostRepository extends ServiceEntityRepository implements PostStorage
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvicePost::class);
    }

    public function findAllPaginated(int $page, int $limit = 10): array
    {
        return $this->createQueryBuilder('x')
            ->select()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function save(AdvicePost $post): void
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    public function delete(AdvicePost $post): void
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }
}
