<?php

namespace App\Repository;

use App\Entity\EmailToNotify;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmailToNotify>
 */
class EmailToNotifyRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailToNotify::class);
    }

    public function add(string $email): void
    {
        $emailEntity = new EmailToNotify();
        $emailEntity->setEmail($email);

        $this->getEntityManager()->persist($emailEntity);
        $this->getEntityManager()->flush();
    }

    public function remove(string $email): void
    {
        $this->createQueryBuilder('email')
            ->where('email.email = :email')
            ->setParameter('email', $email)
            ->getQuery()->execute();
    }
}
