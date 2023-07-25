<?php

namespace Whalephant;

use Puzzle\Configuration;
use Puzzle\Pieces\PathManipulation;
use Whalephant\Services\Generator;
use Whalephant\Services\ExtensionProviders\ArrayProvider;
use Whalephant\Services\ProjectBuilder;
use Whalephant\Framework\Providers;

class Container extends \Pimple\Container
{
    use PathManipulation;

    public const
        WHALEPHANT_FILENAME = 'whalephant.yml',
        VERSION = '8.2.0';

    public function __construct(Configuration $configuration, string $rootDir)
    {
        parent::__construct();

        $this['debug'] = false;
        $this['charset'] = 'UTF-8';
        $this['logger'] = null;
        $this['configuration'] = $configuration;

        $this->initializePaths($rootDir);
        $this->initializeServices();
    }

    private function initializePaths(string $rootDir): void
    {
        $this['root.path'] = $this->enforceEndingSlash($rootDir);
        $this['documentRoot.path'] = $this['root.path'] . 'www' . DIRECTORY_SEPARATOR;
        $this['var.path'] = $this['root.path'] . $this->removeWrappingSlashes($this['configuration']->readRequired('app/var.path')) . DIRECTORY_SEPARATOR;
    }

    protected function initializeServices(): void
    {
        $this->register(new Providers\Twig());

        $this['generator'] = function() {
            return new Generator($this['project.builder'], $this['twig']);
        };

        $this['project.builder'] = function() {
            return new ProjectBuilder($this['extension.provider']);
        };

        $this['extension.provider'] = static function() {
            return new ArrayProvider();
        };
    }
}
