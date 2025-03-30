<?php

namespace App\Controller\DTO\AdminDashboard\Library;

readonly class GetAdvicePostsDTO
{
    public function __construct(
        public string $page
    )
    {
    }
}