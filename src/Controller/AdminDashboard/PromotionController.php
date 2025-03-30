<?php

namespace App\Controller\AdminDashboard;

use App\Contracts\Library\Advice\PostStorage;
use App\Contracts\Library\Advice\PostThumbnailStorage;
use App\Controller\DTO\AdminDashboard\Library\CreateAdviceDTO;
use App\Controller\DTO\AdminDashboard\Library\EditAdviceDTO;
use App\Controller\DTO\AdminDashboard\Library\GetAdvicePostsDTO;
use App\Entity\AdvicePost;
use App\Entity\AdvicePostTag;
use App\Repository\AdvicePostRepository;
use App\Repository\AdvicePostTagRepository;
use App\Service\Base64ImageDecoder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PromotionController extends AbstractController
{
    public function __construct(
        private AdvicePostRepository $postRepository,
        private AdvicePostTagRepository $postTagRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/admin/library/advice/create', 'library.advice.create', methods: ['PUT'])]
    public function create(
        #[MapRequestPayload] CreateAdviceDTO $payload,
        PostThumbnailStorage                 $thumbnailStorage,
        PostStorage                          $postStorage,
    ): JsonResponse
    {
        $post = new AdvicePost();
        $post->setTitle($payload->title);
        $post->setExcerpt($payload->excerpt);
        $post->setBody($payload->body);
        $post->setCreatedAt(new \DateTimeImmutable());

        $existTags = $this->postTagRepository->findManyByNames($payload->tags);

        foreach ($payload->tags as $tag) {
            $tagExist = false;
            foreach ($existTags as $existTag) {
                if ($existTag->getTitle() === $tag) {
                    $tagExist = true;
                    break;
                }
            }

            if (!$tagExist) {
                $newTag = new AdvicePostTag();
                $newTag->setTitle($tag);
                $existTags[] = $newTag;
                $this->entityManager->persist($newTag);
            }
        }

        $this->entityManager->flush();
        $post->setTags($existTags);

        $postStorage->save($post);

        $img = Base64ImageDecoder::decode($payload->thumbnail);
        $imgName = $post->getId() . '.' . $img->extension;
        $thumbnailStorage->store($imgName, $img->data);

        $post->setThumbnail(new File($thumbnailStorage->getFilepath($imgName)));
        $postStorage->save($post);

        return $this->json($post);
    }

    #[Route('/library/advice/list', 'library.advice.list', methods: ['GET'])]
    public function getList(Request $request): JsonResponse
    {
        $payload = new GetAdvicePostsDTO(
            page: $request->request->get('page', 1)
        );

        return $this->json($this->postRepository->findAllPaginated($payload->page));
    }

    #[Route('/library/advice/{post}', 'library.advice.post', methods: ['GET'])]
    public function getSingle(#[MapEntity] AdvicePost $post): JsonResponse
    {
        return $this->json($post);
    }

    #[Route('/admin/library/advice/{post}/edit', 'library.advice.edit', methods: ['PATCH'])]
    public function edit(
        #[MapRequestPayload] EditAdviceDTO   $payload,
        #[MapEntity] AdvicePost              $post,
        PostThumbnailStorage                 $thumbnailStorage,
        PostStorage                          $postStorage,
    ): JsonResponse
    {
        if ($payload->title) {
            $post->setTitle($payload->title);
        }

        if ($payload->excerpt) {
            $post->setExcerpt($payload->excerpt);
        }

        if ($payload->body) {
            $post->setBody($payload->body);
        }

        if ($payload->thumbnail && !str_starts_with($payload->thumbnail, 'http')) {
            $img = Base64ImageDecoder::decode($payload->thumbnail);
            $imgName = $post->getId() . '.' . $img->extension;
            $thumbnailStorage->replace($imgName, $img->data);
        }

        if ($payload->tags) {
            $existTags = $this->postTagRepository->findManyByNames($payload->tags);

            foreach ($payload->tags as $tag) {
                $tagExist = false;
                foreach ($existTags as $existTag) {
                    if ($existTag->getTitle() === $tag) {
                        $tagExist = true;
                        break;
                    }
                }

                if (!$tagExist) {
                    $newTag = new AdvicePostTag();
                    $newTag->setTitle($tag);
                    $existTags[] = $newTag;
                    $this->entityManager->persist($newTag);
                }
            }
            $this->entityManager->flush();
            $post->setTags($existTags);
        }

        $postStorage->save($post);

        return $this->json($post);
    }

    #[Route('/admin/library/advice/{post}/delete', 'library.advice.delete', methods: ['DELETE'])]
    public function delete(
        #[MapEntity] AdvicePost $post,
        PostStorage $postStorage,
    ) : JsonResponse
    {
        $postStorage->delete($post);
        return $this->json(true);
    }
}