<?php

namespace Whalephant\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local;
use Whalephant\Services\Generator;

class Generate extends AbstractCommand
{
    private
        $generator;
    
    public function __construct(Generator $generator)
    {
        parent::__construct();
        
        $this->generator = $generator;
    }
    
    protected function configure(): void
    {
        $this->setName('generate')
            ->setDescription('Generate dockerfile')
            ->addArgument('directory', InputArgument::REQUIRED, 'directory where whalephant.yml is located and where Dockerfile must be generated');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Generating Dockerfile ...');
        
        $directory = $input->getArgument('directory');
        
        if(! is_dir($directory))
        {
            throw new \InvalidArgumentException("$directory does not exist");
        }
        
        $fs = new Filesystem(new Local($directory));
        
        if(! $fs->has(\Whalephant\Application::WHALEPHANT_FILENAME))
        {
            throw new \InvalidArgumentException(\Whalephant\Application::WHALEPHANT_FILENAME . " is missing");
        }
        
        $this->generator->generate($fs);
    }
}
