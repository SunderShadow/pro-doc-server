<?php

namespace App\Controller\Library;

use App\Contracts\Library\Advice\PostStorage;
use App\Contracts\Library\Advice\PostThumbnailStorage;
use App\DTO\AdminDashboard\Library\CreateAdviceDTO;
use App\DTO\AdminDashboard\Library\EditAdviceDTO;
use App\DTO\AdminDashboard\Library\GetAdvicePostsDTO;
use App\Entity\AdvicePost;
use App\Repository\AdvicePostRepository;
use App\Repository\AdvicePostTagRepository;
use App\Service\Base64ImageDecoder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdviceController extends AbstractController
{
    public function __construct(
        private AdvicePostRepository $postRepository,
        private AdvicePostTagRepository $postTagRepository,
        private MailerInterface $mailer,
        private PostStorage $postStorage,
        private PostThumbnailStorage $postThumbnailStorage,
    )
    {
    }

    #[Route('/library/advice/list', 'library.advice.list', methods: ['GET'])]
    public function getPublishedList(Request $request): JsonResponse
    {
        $payload = new GetAdvicePostsDTO(
            page: $request->request->get('page', 1)
        );

        return $this->json($this->postRepository->findAllPublishedPaginated($payload->page));
    }

    #[Route('/library/advice/{post}', 'library.advice.post', methods: ['GET'])]
    public function getPublishedSingle(#[MapEntity] AdvicePost $post): JsonResponse
    {
        if ($post->isPublished()) {
            return $this->json($post);
        } else {
            return $this->json([
                'message' => 'Нет опубликованных постов'
            ], 404);
        }
    }

    #[Route('/admin/library/advice/create', 'library.advice.create', methods: ['PUT'])]
    public function create(
        #[MapRequestPayload] CreateAdviceDTO $payload,
    ): JsonResponse
    {
        $post = new AdvicePost();
        $post->setTitle($payload->title);
        $post->setExcerpt($payload->excerpt);
        $post->setBody($payload->body);
        $post->setCreatedAt(new \DateTimeImmutable());

        $tags = $this->postTagRepository->registerTags($payload->tags);

        $this->postStorage->bindTags($post, $tags);

        $img = Base64ImageDecoder::decode($payload->thumbnail);
        $imgName = $post->getId() . '.' . $img->extension;
        $this->postThumbnailStorage->store($imgName, $img->data);

        $post->setThumbnail(new File($this->postThumbnailStorage->getFilepath($imgName)));
        $this->postStorage->save($post);

        return $this->json($post);
    }

    #[Route('/admin/library/advice/list', 'library.advice.list', methods: ['GET'])]
    public function getList(Request $request): JsonResponse
    {
        $payload = new GetAdvicePostsDTO(
            page: $request->request->get('page', 1)
        );

        return $this->json($this->postRepository->findAllPaginated($payload->page));
    }

    #[Route('/admin/library/advice/{post}', 'library.advice.post', methods: ['GET'])]
    public function getSingle(#[MapEntity] AdvicePost $post): JsonResponse
    {
        return $this->json($post);
    }

    #[Route('/admin/library/advice/{post}/edit', 'library.advice.edit', methods: ['PATCH'])]
    public function edit(
        #[MapRequestPayload] EditAdviceDTO   $payload,
        #[MapEntity] AdvicePost              $post,
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
            $this->postThumbnailStorage->replace($imgName, $img->data);
        }

        if ($payload->tags) {
            $tags = $this->postTagRepository->registerTags($payload->tags);

            $this->postStorage->bindTags($post, $tags);
        }

        $this->postStorage->save($post);

        return $this->json($post);
    }

    #[Route('/admin/library/advice/{post}/delete', 'library.advice.delete', methods: ['DELETE'])]
    public function delete(
        #[MapEntity] AdvicePost $post
    ) : JsonResponse
    {
        $this->postStorage->delete($post);
        return $this->json(true);
    }

    #[Route('/admin/library/advice/{post}/publish', 'library.advice.publish', methods: ['PATCH'])]
    public function publish(
        #[MapEntity] AdvicePost $post
    )
    {
        $this->postStorage->publish($post);
        return $this->json(true);
    }

    #[Route('/admin/library/advice/{post}/draft', 'library.advice.draft', methods: ['PATCH'])]
    public function draft(
        #[MapEntity] AdvicePost $post
    )
    {
        $this->postStorage->draft($post);
        return $this->json(true);
    }
}