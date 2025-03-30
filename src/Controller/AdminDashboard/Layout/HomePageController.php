<?php

namespace App\Controller\AdminDashboard\Layout;

use App\Contracts\Layout\ThumbnailStorage;
use App\Controller\DTO\AdminDashboard\Layout\HomePageConfigDTO;
use App\Entity\PageConfig;
use App\Repository\PageConfigRepository;
use App\Service\Base64ImageDecoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    const string CONFIG_NAME = 'page.home';

    public function __construct(
        private PageConfigRepository $pageConfigRepository,
        private ThumbnailStorage     $thumbnailStorage,
        #[Autowire('%app.layout.thumbnail.folder.web%')] private string $thumbnailFolder,
    )
    {
    }

    #[Route('/admin/pages/home/edit', methods: ['PATCH'], format: 'json')]
    public function edit(
        #[MapRequestPayload] HomePageConfigDTO $payload
    )
    {
        $pageConfig = $this->pageConfigRepository->findByPageName(self::CONFIG_NAME);

        if (empty($pageConfig)) {
            $pageConfig = new PageConfig();
            $pageConfig->setPageName(self::CONFIG_NAME);
        }

        $config = [
            'slider' => [],
            'qa' => $payload->qa
        ];

        // Save new slider files
        $thumbnailsToSave = [];
        foreach ($payload->slider as $slide) {
            $sliderSize = array_push($config['slider'], (array) $slide);

            if (str_starts_with($slide->thumbnail, 'http')) {
                $thumbnailsToSave[] = $config['slider'][$sliderSize - 1]['thumbnail'] = basename($slide->thumbnail);
                continue;
            }

            $decodedImage = Base64ImageDecoder::decode($slide->thumbnail);
            $imgName = self::CONFIG_NAME . '.slider.' . time() . '.' . $decodedImage->extension;
            $this->thumbnailStorage->store($imgName, $decodedImage->data);

            $config['slider'][$sliderSize - 1]['thumbnail'] = $imgName;
        }

        if (isset($pageConfig->getConfig()['slider'])) {
            foreach ($pageConfig->getConfig()['slider'] as $slide) {
                if (false === array_search($slide['thumbnail'], $thumbnailsToSave)) {
                    $this->thumbnailStorage->remove($slide['thumbnail']);
                }
            }
        }

        $this->pageConfigRepository->set($pageConfig, $config);

        return $this->get();
    }

    #[Route('/pages/home/config', methods: ['GET'], format: 'json')]
    public function get()
    {
        $pageConfig = $this->pageConfigRepository->findByPageName(self::CONFIG_NAME);
        $config = null;

        if ($pageConfig) {
            $config = $pageConfig->getConfig();
            foreach ($config['slider'] as &$slide) {
                if (!str_starts_with($slide['thumbnail'], 'http')) {
                    $slide['thumbnail'] = 'http' . '://' . $_SERVER['HTTP_HOST'] . '/' . $this->thumbnailFolder . '/' . $slide['thumbnail'];
                }
            }
        }

        return $this->json($config);
    }
}