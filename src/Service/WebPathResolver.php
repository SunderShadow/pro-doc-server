<?php

namespace App\Service;

class WebPathResolver
{
    public function __construct(
        private string $namespace
    )
    {
    }

    public function resolve(string $filename): string
    {
        $protocol = 'http' . ($_SERVER['HTTPS'] ? 's' : '');
        $host = $_SERVER['HTTP_HOST'];

        $webRoot = $protocol . '://' . $host . '/' . $this->namespace;

        return $webRoot . '/' . $filename;
    }
}