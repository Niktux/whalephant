<?php

declare(strict_types = 1);

namespace Whalephant\Services;

use Twig\Environment;
use Puzzle\Configuration\Yaml;
use Gaufrette\Filesystem;
use Puzzle\PrefixedConfiguration;

class Generator
{
    private ProjectBuilder
        $projectBuilder;
    private Environment
        $twig;
    
    public function __construct(ProjectBuilder $builder, Environment $twig)
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
            'systemPackages' => $recipe->systemPackages(),
            'peclExtensions' => $recipe->peclExtensions(),
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
