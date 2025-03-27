<?php

namespace App\DTO\AdminDashboard\Layout;

use App\DTO\AdminDashboard\Layout\Footer\SocialDTO;

readonly class FooterConfigDTO
{
    public function __construct(
        public ?string $phone,
        public ?string $email,
        public ?SocialDTO $social,
        public ?array $banners // Base64 encoded images
    ) {}
}