<?php

namespace Whalephant\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Whalephant\Services\ExtensionProvider;

class Extensions extends AbstractCommand
{
    private
        $provider;
    
    public function __construct(ExtensionProvider $provider)
    {
        parent::__construct();
        
        $this->provider = $provider;
    }
    
    protected function configure(): void
    {
        $this->setName('extensions')
        ->setDescription('List supported extensions');
    }
    
    protected function doExecute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Suported extensions are :');

        foreach($this->provider->listNames() as $name)
        {
            $output->writeln("<comment>$name</comment>");
        }
    }
}
