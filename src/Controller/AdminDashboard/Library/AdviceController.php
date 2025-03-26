<?php

namespace App\Controller\AdminDashboard\Library;

use App\Contracts\Library\Advice\PostStorage;
use App\Contracts\Library\Advice\PostThumbnailStorage;
use App\DTO\AdminDashboard\Library\CreateAdviceDTO;
use App\DTO\AdminDashboard\Library\EditAdviceDTO;
use App\DTO\AdminDashboard\Library\GetAdvicePostsDTO;
use App\Entity\AdvicePost;
use App\Repository\AdvicePostRepository;
use App\Service\Base64ImageDecoder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AdviceController extends AbstractController
{
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

        $postStorage->save($post);

        $img = Base64ImageDecoder::decode($payload->thumbnail);
        $imgName = $post->getId() . '.' . $img->extension;
        $thumbnailStorage->store($imgName, $img->data);

        $post->setThumbnail(new File($thumbnailStorage->getFilepath($imgName)));
        $postStorage->save($post);

        return $this->json($post);
    }

    #[Route('/library/advice/list', 'library.advice.list', methods: ['GET'])]
    public function getList(
        Request $request,
        AdvicePostRepository $postRepository
    ): JsonResponse
    {
        $payload = new GetAdvicePostsDTO(
            page: $request->request->get('page', 1)
        );

        return $this->json($postRepository->findAllPaginated($payload->page));
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

        if ($payload->thumbnail) {
            $img = Base64ImageDecoder::decode($payload->thumbnail);
            $imgName = $post->getId() . '.' . $img->extension;
            $thumbnailStorage->replace($imgName, $img->data);
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