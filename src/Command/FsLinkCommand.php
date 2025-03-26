<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:fs:link',
    description: 'Add a short description for your command',
)]
class FsLinkCommand extends Command
{
    private readonly string $assetsFolderpath;

    public function __construct(
        #[Autowire('%kernel.project_dir%')] private readonly string $projectRoot,
        private readonly Filesystem $fs
    )
    {
        $this->assetsFolderpath = $this->projectRoot . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'assets';
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->fs->symlink(
            $this->projectRoot . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'library',
            $this->assetsFolderpath . DIRECTORY_SEPARATOR . '/library'
        );

        return self::SUCCESS;
    }
}
