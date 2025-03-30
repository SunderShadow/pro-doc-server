<?php

namespace App\Repository;

use App\Entity\PageConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PageConfig>
 */
class PageConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageConfig::class);
    }

    public function findByPageName(string $pageName): ?PageConfig
    {
        return $this->createQueryBuilder('x')->select()
            ->where('x.pageName = :page_name')
            ->setParameter('page_name', $pageName)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function set(string|PageConfig $pageConfig, array $data): PageConfig
    {
        if (is_string($pageConfig)) {
            $pageConfig = $this->findByPageName($pageConfig);
        }

        if (empty($pageConfig)) {
            $pageConfig = new PageConfig();
            $pageConfig->setPageName($pageConfig->getPageName());
        }

        $pageConfig->setConfig($data);
        $this->getEntityManager()->persist($pageConfig);
        $this->getEntityManager()->flush();


        return $pageConfig;
    }
}
