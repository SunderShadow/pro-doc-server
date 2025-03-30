<?php

namespace App\Controller\DTO\AdminDashboard\Layout\Footer;

readonly class SocialDTO
{
    public function __construct(
        public string $vk,
        public string $telegram
    ) {}
}