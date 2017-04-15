<?php

namespace Whalephant\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local;
use Whalephant\Application;
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
    
    protected function configure()
    {
        $this->setName('generate')
            ->setDescription('Generate dockerfile')
            ->addArgument('directory', InputArgument::REQUIRED, 'directory where whalephant.yml is located and where Dockerfile must be generated');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating Dockerfile ...');
        
        $directory = $input->getArgument('directory');
        
        if(! is_dir($directory))
        {
            throw new \InvalidArgumentException("$directory does not exist");
        }
        
        $fs = new Filesystem(new Local($directory));
        
        if(! $fs->has(Application::WHALEPHANT_FILENAME))
        {
            throw new \InvalidArgumentException(Application::WHALEPHANT_FILENAME . " is missing");
        }
        
        $this->generator->generate($fs);
    }
}
