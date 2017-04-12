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
use Whalephant\Services\ProjectBuilder;
use Puzzle\Configuration\Yaml;
use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local;
use Puzzle\PrefixedConfiguration;
use Whalephant\Services\ExtensionProviders\ArrayProvider;

class Generate extends AbstractCommand
{
    private
        $rootPath,
        $twig;
    
    public function __construct(\Twig_Environment $twig, string $rootPath)
    {
        parent::__construct();
        
        $this->twig = $twig;
        $this->rootPath = $rootPath;
    }
    
    protected function configure()
    {
        $this->setName('generate')
            ->setDescription('Generate dockerfile');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating Dockerfile ...');

        $config = new Yaml(new Filesystem(new Local($this->rootPath . 'recipes/')));
        $config = new PrefixedConfiguration($config, 'test');
        
        $extensionProvider = new ArrayProvider();
        
        $builder = new ProjectBuilder($config, $extensionProvider);
        $project = $builder->build();
        
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
