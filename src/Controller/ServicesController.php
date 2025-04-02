<?php

namespace App\Controller;

use App\DTO\AdminDashboard\Service\ServiceAddDTO;
use App\DTO\AdminDashboard\Service\ServiceEditDTO;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use App\Service\Base64ImageDecoder;
use App\Service\ThumbnailStorage;
use App\Service\WebPathResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ServicesController extends AbstractController
{
    public function __construct(
        private WebPathResolver $serviceThumbnailWebpathResolver,
        private ThumbnailStorage $serviceThumbnailStorage,
        private ServiceRepository $serviceRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/admin/service/add', methods: ['PUT'])]
    public function addService(
        #[MapRequestPayload] ServiceAddDTO $payload
    )
    {
        $decodedImage = Base64ImageDecoder::decode($payload->thumbnail);
        $decodedImageName = time() . '.' . $decodedImage->extension;
        $this->serviceThumbnailStorage->store($decodedImageName, $decodedImage->data);

        $service = new Service();
        $service->setId($payload->id);
        $service->setName($payload->name);
        $service->setThumbnail($decodedImageName);

        $this->entityManager->persist($service);
        $this->entityManager->flush();

        return $this->json($service);
    }

    #[Route('/admin/service/{service}/edit', methods: ['PATCH'])]
    public function editService(
        #[MapRequestPayload] ServiceEditDTO $payload,
        #[MapEntity] Service $service
    )
    {
        if ($payload->name) {
            $service->setName($payload->name);
        }

        if ($payload->thumbnail) {
            $decodedImage = Base64ImageDecoder::decode($payload->thumbnail);
            $decodedImageName = time() . '.' . $decodedImage->extension;

            $this->serviceThumbnailStorage->store($decodedImageName, $decodedImage->data);
            $this->serviceThumbnailStorage->remove($service->getThumbnail());

            $service->setThumbnail($decodedImageName);
        }       
        $this->entityManager->persist($service);
        $this->entityManager->flush($service);

        return $this->json($service);
    }

    #[Route('/service/list', methods: ['GET'])]
    public function getServiceList()
    {
        $services = $this->serviceRepository->findAll();

        foreach ($services as $service) {
            $service->setThumbnail($this->serviceThumbnailWebpathResolver->resolve($service->getThumbnail()));
        }

        return $this->json($services);
    }
}