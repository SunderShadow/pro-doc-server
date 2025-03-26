<?php

namespace App\Contracts;

interface ThumbnailStorage
{
    public function store(string $name, string $data): void;

    public function remove(string $name): void;

    public function replace(string $name, string $data): void;

    public function getFilepath(string $name): string;
}