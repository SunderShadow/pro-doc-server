<?php

namespace App\Controller\AdminDashboard\Layout;

use App\Contracts\Layout\ThumbnailStorage;
use App\Controller\DTO\AdminDashboard\Layout\FooterConfigDTO;
use App\Entity\PageConfig;
use App\Repository\PageConfigRepository;
use App\Service\Base64ImageDecoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class FooterController extends AbstractController
{
    const string CONFIG_NAME = 'layout.footer';

    public function __construct(
        private PageConfigRepository $pageConfigRepository,
        private ThumbnailStorage     $thumbnailStorage,
        #[Autowire('%app.layout.thumbnail.folder.web%')] private string $thumbnailFolder
    )
    {
    }

    #[Route('/admin/layout/footer/edit', methods: ['PATCH'], format: 'json')]
    public function edit(
        #[MapRequestPayload] FooterConfigDTO $payload,
    )
    {
        $pageConfig = $this->pageConfigRepository->findByPageName(self::CONFIG_NAME);

        if (empty($pageConfig)) {
            $pageConfig = new PageConfig();
            $pageConfig->setPageName(self::CONFIG_NAME);
        }

        $config = [
            "phone" => $payload->phone,
            "email" => $payload->email,
            "social" => [
                "vk" => $payload->social->vk,
                "telegram" => $payload->social->telegram
            ],
            "banners" => [] // Base64 encoded images
        ];

        // Save new slider files
        $bannersToSave = [];
        foreach ($payload->banners as $banner) {
            $sliderSize = array_push($config['banners'], $banner);

            if (str_starts_with($banner, 'http')) {
                $bannersToSave[] = $config['banners'][$sliderSize - 1] = basename($banner);
                continue;
            }

            $decodedImage = Base64ImageDecoder::decode($banner);
            $imgName = self::CONFIG_NAME . '.banner.' . time() . '.' . $decodedImage->extension;
            $this->thumbnailStorage->store($imgName, $decodedImage->data);

            $config['banners'][$sliderSize - 1] = $imgName;
        }

        if (isset($pageConfig->getConfig()['banners'])) {
            foreach ($pageConfig->getConfig()['banners'] as $banner) {
                if (false === array_search($banner, $bannersToSave)) {
                    $this->thumbnailStorage->remove($banner);
                }
            }
        }

        $this->pageConfigRepository->set($pageConfig, $config);

        return $this->get();
    }

    #[Route('/layout/footer/get', methods: ['GET'], format: 'json')]
    public function get()
    {
        $pageConfig = $this->pageConfigRepository->findByPageName(self::CONFIG_NAME);
        $config = null;

        if ($pageConfig) {
            $config = $pageConfig->getConfig();
            foreach ($config['banners'] as &$banner) {
                $banner = 'http' . ($_SERVER['HTTPS'] ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . $this->thumbnailFolder . '/' . $banner;
            }
        }

        return $this->json($config);
    }
}