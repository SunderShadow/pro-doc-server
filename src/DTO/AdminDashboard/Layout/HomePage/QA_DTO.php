<?php

namespace App\DTO\AdminDashboard\Layout\HomePage;

readonly class QA_DTO
{
    public function __construct(
        public string $title,
        public string $description,
    )
    {
    }
}