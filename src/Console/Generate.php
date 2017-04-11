<?php

namespace Whalephant\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Whalephant\Model\Project;
use Whalephant\Model\Pecl;
use Whalephant\Model\Extensions\Xdebug;
use Whalephant\Model\Php;
use Whalephant\Model\Extensions\Amqp;
use Whalephant\Model\Extensions\Zlib;
use Whalephant\Model\Extensions\Meminfo;

class Generate extends AbstractCommand
{
    private
        $twig;
    
    public function __construct(\Twig_Environment $twig)
    {
        parent::__construct();
        
        $this->twig = $twig;
    }
    
    protected function configure()
    {
        $this->setName('generate')
            ->setDescription('Generate dockerfile');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating Dockerfile ...');
        
        $project = new Project('whalephant-test', new Php('5.6'));
        
        $project->addExtension(new Xdebug());
        $project->addExtension(new Amqp());
        $project->addExtension(new Zlib());
        $project->addExtension(new Meminfo());
        
        $recipe = $project->getRecipe();
        
        $dockerfile = $this->twig->render('layout.twig', [
            'php' => $project->getPhp(),
            'system' => [
                'packages' => $recipe->getPackages(),
            ],
            'pecl' => [
                'install' => $recipe->getPeclPackagesToInstall(),
                'enable' => $recipe->getPeclPackagesToEnable(),
            ],
            'macroList' => $recipe->getMacros(),
            'project' => $project,
        ]);
        
        file_put_contents("Dockerfile", $dockerfile);
        file_put_contents("php.ini",
            implode("\n", $recipe->getIniDirectives()) . "\n"
        );
    }
}
