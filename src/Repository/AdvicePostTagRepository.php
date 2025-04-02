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

    public function removeUnrelatedTags(): void
    {
        foreach($this->findAll() as $tag) {
            if ($tag->getPosts()->count() === 0) {
                $this->getEntityManager()->remove($tag);
            }
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @param array<string> $tags
     * @return array<AdvicePostTag>
     */
    public function registerTags(array $tags): array
    {
        $registeredTags = $this->findManyByNames($tags);

        foreach ($tags as $tag) {
            $tagExist = false;
            foreach ($registeredTags as $existTag) {
                if ($existTag->getTitle() === $tag) {
                    $tagExist = true;
                    break;
                }
            }

            if (!$tagExist) {
                $newTag = new AdvicePostTag();
                $newTag->setTitle($tag);
                $registeredTags[] = $newTag;
                $this->getEntityManager()->persist($newTag);
            }
        }

        return $registeredTags;
    }
}
