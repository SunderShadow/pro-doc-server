<?php

namespace App\Controller\DTO\AdminDashboard\Layout\HomePage;

readonly class SliderDTO
{
    public function __construct(
        public string $title,
        public string $thumbnail,
        public string $description
    )
    {
    }
}