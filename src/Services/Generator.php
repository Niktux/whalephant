<?php

declare(strict_types = 1);

namespace Whalephant\Services;

use Whalephant\Services\ProjectBuilder;
use Puzzle\Configuration\Yaml;
use Gaufrette\Filesystem;
use Puzzle\PrefixedConfiguration;

class Generator
{
    private
        $projectBuilder,
        $twig;
    
    public function __construct(ProjectBuilder $builder, \Twig_Environment $twig)
    {
        $this->projectBuilder = $builder;
        $this->twig = $twig;
    }
    
    public function generate(Filesystem $fs): void
    {
        $config = new Yaml($fs);
        $config = new PrefixedConfiguration($config, 'whalephant');
        
        $project = $this->projectBuilder->build($config);
        
        $recipe = $project->getRecipe();
        
        $dockerfile = $this->twig->render('layout.twig', [
                'php' => $project->getPhp(),
                'system' => [
                'packages' => $recipe->getPackages(),
            ],
            'pecl' => [
                'install' => $recipe->getPeclPackagesToInstall(),
                'configure' => $recipe->getPeclPackagesToConfigure(),
                'enable' => $recipe->getPeclPackagesToEnable(),
            ],
            'extensions' => [
                'install' => $recipe->getExtensionsToInstall(),
            ],
            'macroList' => $recipe->getMacros(),
            'project' => $project,
            'needAutomake' => $recipe->getAutomakeNeeded(),
        ]);
        
        $fs->write("Dockerfile", $dockerfile, true);
        $fs->write("php.ini",
            implode("\n", $recipe->getIniDirectives()) . "\n",
            true
        );
    }
}
