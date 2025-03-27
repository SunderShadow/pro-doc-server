<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * @throws \Exception
     */
    public function setImage(int $id, string $image): Service
    {
        $service = $this->find($id);

        if (!$service) {
            throw new \Exception("Undefined service with ID: $id");
        }

        $service->setThumbnail($image);
        $this->getEntityManager()->persist($service);
        $this->getEntityManager()->flush();

        return $service;
    }
}
