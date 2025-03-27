<?php

namespace App\Controller\AdminDashboard\Layout;

use App\Contracts\Layout\ThumbnailStorage;
use App\DTO\AdminDashboard\Layout\FooterConfigDTO;
use App\Entity\PageConfig;
use App\Repository\PageConfigRepository;
use App\Service\Base64ImageDecoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class FooterController extends AbstractController
{
    const string CONFIG_NAME = 'layout.footer';

    public function __construct(
        private PageConfigRepository $pageConfigRepository,
        private ThumbnailStorage     $thumbnailStorage
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

        $payloadArray = (array)$payload;
        $arrayConfig = $pageConfig->getConfig();

        // Remove all unrequested data
        foreach ($payloadArray as $key => $value) {
            if ($value === null) {
                unset($payloadArray[$key]);
            }
        }

        // Delete old banners files
        if (isset($pageConfig->getConfig()['banners'])) {
            foreach ($pageConfig->getConfig()['banners'] as $banner) {
                $this->thumbnailStorage->remove($banner);
            }
        }

        // Save new banners
        if (isset($payloadArray['banners'])) {
            foreach ($payloadArray['banners'] as &$banner) {
                $img = Base64ImageDecoder::decode($banner);
                $imgName = self::CONFIG_NAME . '.banner.' . time() . '.' . $img->extension;
                $this->thumbnailStorage->store($imgName, $img->data);

                $banner = $imgName;
            }
        }

        $this->pageConfigRepository->set($pageConfig, array_merge($arrayConfig, $payloadArray));

        return $this->json($pageConfig);
    }

    #[Route('/layout/footer/get', methods: ['GET'], format: 'json')]
    public function get(
        #[Autowire('%app.layout.thumbnail.folder.web%')] $thumbnailFolder,
        PageConfigRepository $pageConfig
    )
    {
        $pageConfig = $pageConfig->findByPageName(self::CONFIG_NAME);
        $config = null;

        if ($pageConfig) {
            $config = $pageConfig->getConfig();
            foreach ($config['banners'] as &$banner) {
                $banner = 'http' . ($_SERVER['HTTPS'] ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/' . $thumbnailFolder . '/' . $banner;
            }
        }

        return $this->json($config);
    }
}