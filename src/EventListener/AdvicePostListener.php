<?php

namespace App\EventListener;

use App\Entity\AdvicePost;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsEntityListener(event: Events::postLoad, method: 'setThumbnailPrefix', entity: AdvicePost::class)]
final class AdvicePostListener
{
    public function __construct(
        #[Autowire ('%app.library.advice.thumbnail.folder.web%')] private readonly string $thumbnailWebPath,
        #[Autowire ('%app.library.advice.thumbnail.folder.web%')] private readonly string $thumbnailLocalPath
    )
    {
    }

    public function setThumbnailPrefix(AdvicePost $post): void
    {
        $post->setThumbnailURLPrefix($_SERVER['HTTP_HOST'] . '/' . $this->thumbnailWebPath);
        $post->setThumbnailFilepathPrefix($this->thumbnailLocalPath);
    }
}
