<?php

namespace App\Repository;

use App\Contracts\Library\Advice\PostStorage;
use App\Contracts\NotificationService;
use App\Entity\AdvicePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mime\Email;

/**
 * @extends ServiceEntityRepository<AdvicePost>
 */
class AdvicePostRepository extends ServiceEntityRepository implements PostStorage
{

    public function __construct(
        ManagerRegistry $registry,
        private NotificationService $notificator,
        #[Autowire('%app.front.app.base_url%')] private string $appBaseUrl
    )
    {
        parent::__construct($registry, AdvicePost::class);
    }

    public function findAllPaginatedQuery(int $page, int $limit = 10): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('post')
            ->select()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);
    }

    public function findAllPaginated(int $page, int $limit = 10): array
    {
        return $this->findAllPaginatedQuery($page, $limit)
            ->getQuery()->getResult();
    }

    public function findAllPublishedPaginated(int $page, int $limit = 10): array
    {
        return $this
            ->findAllPaginatedQuery($page, $limit)
            ->where("post.isPublished = 1")
            ->getQuery()->getResult();
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

    public function bindTags(AdvicePost $post, array $tags): void
    {
        $oldTags = $post->getTags()->toArray();

        // Clear old tags
        foreach ($post->getTags() as $tag) {
            $tag->removePost($post);
        }

        $post->getTags()->clear();

        // Bind new tags
        foreach ($tags as $tag) {
            $tag->addPost($post);
            $this->getEntityManager()->persist($tag);

            $post->addTag($tag);
        }

        // Remove tag if no related posts
        foreach ($oldTags as $oldTag) {
            if ($oldTag->getPosts()->count() === 0) {
                $this->getEntityManager()->remove($oldTag);
            }
        }

        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    public function publish(AdvicePost $post): void
    {
        $post->publish();

        $this->notificator->notify(function (Email $email) use ($post){
            $href = $this->appBaseUrl . '/library/advices/article/kakoy-holesterin';

            return $email
                ->subject('Вышла новая статья: ' . $post->getTitle())
                ->html("<a href=\"$href\">Посмотреть на статю</a>");
        });

        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    public function draft(AdvicePost $post): void
    {
        $post->draft();
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }
}
