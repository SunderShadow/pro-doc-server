<?php

namespace App\Entity;

use App\Repository\AdvicePostTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: AdvicePostTagRepository::class)]
class AdvicePostTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private string $title;

    #[ORM\ManyToMany(targetEntity: AdvicePost::class, inversedBy: 'tags')]
    private Collection $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Ignore]
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(AdvicePost $post): self
    {
        $this->posts->add($post);

        return $this;
    }

    public function removePost(AdvicePost $post): self
    {
        $this->posts->removeElement($post);

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
