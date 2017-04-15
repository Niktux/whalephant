<?php

declare(strict_types = 1);

namespace Whalephant\Services;

use Whalephant\Services\ProjectBuilder;
use Puzzle\Configuration\Yaml;
use Gaufrette\Filesystem;
use Puzzle\PrefixedConfiguration;
use Whalephant\Services\ExtensionProviders\ArrayProvider;

class Generator
{
    private
        $twig;
    
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    
    public function generate(Filesystem $fs)
    {
        $config = new Yaml($fs);
        $config = new PrefixedConfiguration($config, 'whalephant');
        
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
        
        $fs->write("Dockerfile", $dockerfile, true);
        $fs->write("php.ini",
            implode("\n", $recipe->getIniDirectives()) . "\n",
            true
        );
    }
}
