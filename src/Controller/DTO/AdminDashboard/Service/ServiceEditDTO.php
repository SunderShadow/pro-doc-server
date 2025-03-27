<?php

namespace App\Controller\DTO\AdminDashboard\Service;

readonly class ServiceEditDTO
{
    public function __construct(
        public ?string $name,
        public ?string $thumbnail // Base64 encoding
    )
    {
    }
}