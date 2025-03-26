<?php

namespace App\DTO\AdminDashboard\Library;

readonly class CreateAdviceDTO
{
    public function __construct(
        public string $title,
        public string $thumbnail, // Base64 encoded image
        public string $body,
        public string $excerpt
    )
    {
    }
}