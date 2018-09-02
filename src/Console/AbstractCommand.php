<?php

namespace Whalephant\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $app = $this->getApplication();
        
        if($app instanceof Application)
        {
            $output->writeln($app->getLogo());
        }

        $this->doExecute($input, $output);
    }
        
    abstract protected function doExecute(InputInterface $input, OutputInterface $output): void;
}
