<?php

namespace Whalephant\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Version extends Command
{
    protected function configure(): void
    {
        $this->setName('version')
        ->setDescription('Show whalephant version');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln(\Whalephant\Application::VERSION);
    }
}
