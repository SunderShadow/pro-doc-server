<?php

namespace App\Entity;

use App\DTO\AdminDashboard\Layout\FooterConfigDTO;
use App\Repository\PageConfigRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: PageConfigRepository::class)]
class PageConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $pageName;

    #[ORM\Column(type: 'json')]
    private array $config = [];

    #[Ignore]
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPageName(): string
    {
        return $this->pageName;
    }

    public function setPageName(string $pageName): self
    {
        $this->pageName = $pageName;
        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }
}
