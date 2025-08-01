<?php

namespace App\Entity;

use App\Repository\AdvicePostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: AdvicePostRepository::class)]
class AdvicePost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $title;

    #[ORM\Column(nullable: true)]
    private string $thumbnailExtension;
    private string $thumbnailUrl;
    private string $thumbnailFilepath;

    #[ORM\Column]
    private bool $isPublished = false;
    #[ORM\Column(type: 'time_immutable', nullable: true)]
    private ?\DateTimeInterface $publishedAt;

    #[ORM\Column(type: 'text')]
    private string $excerpt;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(type: 'time_immutable', options: ['default' => 'now()'])]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToMany(targetEntity: AdvicePostTag::class, mappedBy: 'posts')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    /**
     * @return Collection<AdvicePostTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(AdvicePostTag $tag): self
    {
        $this->tags->add($tag);

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    #[Ignore]
    public function getThumbnailFilepath(): string
    {
        return $this->thumbnailFilepath;
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnail(File $thumbnail): self
    {
        $this->thumbnailExtension = $thumbnail->getExtension();
        return $this;
    }

    public function setThumbnailURLPrefix(string $prefix): self
    {
        $this->thumbnailUrl = $prefix . '/' . $this->id . '.' . $this->thumbnailExtension;
        return $this;
    }

    public function setThumbnailFilepathPrefix(string $prefix): self
    {
        $this->thumbnailFilepath = $prefix . '/' . $this->id . '.' . $this->thumbnailExtension;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function publish(): void
    {
        $this->isPublished = true;
        $this->publishedAt = new \DateTimeImmutable();
    }

    public function isDraft(): bool
    {
        return !$this->isPublished();
    }

    public function draft(): void
    {
        $this->isPublished = false;
        $this->publishedAt = null;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }
}
