<?php

namespace App\Controller\DTO\AdminDashboard\Service;

readonly class ServiceAddDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $thumbnail // Base64 encoding
    )
    {
    }
}