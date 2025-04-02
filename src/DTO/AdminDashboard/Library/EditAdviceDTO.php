<?php

namespace App\DTO\AdminDashboard\Library;
use Symfony\Component\Validator\Constraints as Assert;

readonly class EditAdviceDTO
{
    public function __construct(
        public ?string $title,
        public ?string $thumbnail, // Base64 encoded image
        public ?string $body,
        public ?string $excerpt,

        #[Assert\All([new Assert\Type('string')])]
        public array $tags
    )
    {
    }
}