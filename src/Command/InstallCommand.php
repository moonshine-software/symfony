<?php

declare(strict_types=1);

namespace MoonShine\Symfony\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand('moonshine:install')]
final class InstallCommand extends Command
{
    public function __construct(private string $projectDir, ?string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fs = new Filesystem();

        $fs->copy(
            __DIR__ . '/../../recipe/routes/moonshine.yaml',
            $this->projectDir . '/config/routes/moonshine.yaml'
        );

        $fs->symlink(
            $this->projectDir . '/vendor/moonshine/ui/dist',
            $this->projectDir . '/public/vendor/moonshine',
        );

        $fs->mkdir($this->projectDir . '/src/MoonShine');
        $fs->mkdir($this->projectDir . '/src/MoonShine/Resources');
        $fs->mkdir($this->projectDir . '/src/MoonShine/Pages');
        $fs->mkdir($this->projectDir . '/src/MoonShine/Layouts');

        return Command::SUCCESS;
    }
}