<?php

namespace App\DTO\AdminDashboard\Library;

readonly class GetAdvicePostsDTO
{
    public function __construct(
        public string $page
    )
    {
    }
}