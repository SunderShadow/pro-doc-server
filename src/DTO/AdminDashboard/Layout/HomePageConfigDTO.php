<?php

namespace App\DTO\AdminDashboard\Layout;

use App\DTO\AdminDashboard\Layout\HomePage\QA_DTO;
use App\DTO\AdminDashboard\Layout\HomePage\SliderDTO;

readonly class HomePageConfigDTO
{
    /**
     * @param SliderDTO[] $slider
     * @param QA_DTO[] $qa
     */
    public function __construct(
        public array $slider,
        public array $qa,
    ) {}
}