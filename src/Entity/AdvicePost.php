<?php

namespace App\Entity;

use App\Repository\AdvicePostRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Context;
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
    private string $thumbnailURL;
    private string $thumbnailFilepath;

    #[ORM\Column(type: 'text')]
    private string $excerpt;

    #[ORM\Column(type: 'text')]
    private string $body;

    #[ORM\Column(type: 'time_immutable', options: ['default' => 'now()'])]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToMany(targetEntity: AdvicePostTag::class, inversedBy: 'posts')]
    private Collection $tags;

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

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): self
    {
        $this->tags = $tags;
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

    public function getThumbnailURL(): string
    {
        return $this->thumbnailURL;
    }

    public function setThumbnail(File $thumbnail): self
    {
        $this->thumbnailExtension = $thumbnail->getExtension();
        return $this;
    }

    public function setThumbnailURLPrefix(string $prefix): self
    {
        $this->thumbnailURL = $prefix . '/' . $this->id . '.' . $this->thumbnailExtension;
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
}
