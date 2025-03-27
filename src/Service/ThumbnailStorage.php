<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class ThumbnailStorage implements \App\Contracts\ThumbnailStorage
{
    public function __construct(
        private readonly string $rootPath,
        private readonly Filesystem $fs,
    )
    {
    }

    public function store(string $name, string $data): void
    {
        $this->fs->appendToFile($this->getFilepath($name), $data);
    }

    public function remove(string $name): void
    {
        $this->fs->remove($this->getFilepath($name));
    }

    public function replace(string $name, string $data): void
    {
        if ($this->fs->exists($this->getFilepath($name))) {
            $this->remove($name);
        }

        $this->store($name, $data);
    }

    public function getFilepath(string $name): string
    {
        return $this->rootPath . '/' . $name;
    }
}