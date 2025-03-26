<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class ThumbnailStorage implements \App\Contracts\ThumbnailStorage
{
    public readonly string $rootPath;

    public function __construct(
        private readonly string $namespace,
        private readonly Filesystem $fs,
        #[Autowire('%kernel.project_dir%')] private readonly string $projectRoot,
    )
    {
        $this->rootPath = $this->projectRoot . '/var/' . $this->namespace;
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