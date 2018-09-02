<?php

namespace Whalephant;

use Silex\Provider\SessionServiceProvider;
use Onyx\Providers;
use Whalephant\Services\Generator;
use Whalephant\Services\ExtensionProviders\ArrayProvider;
use Whalephant\Services\ProjectBuilder;

class Application extends \Onyx\Application
{
    public const
        WHALEPHANT_FILENAME = 'whalephant.yml',
        VERSION = '0.8.0';

    protected function registerProviders(): void
    {
        $this->register(new SessionServiceProvider());
        $this->register(new Providers\Twig());
    }

    protected function initializeServices(): void
    {
        $this->configureTwig();

        $this['generator'] = function() {
            return new Generator($this['project.builder'], $this['twig']);
        };

        $this['project.builder'] = function() {
            return new ProjectBuilder($this['extension.provider']);
        };

        $this['extension.provider'] = function() {
            return new ArrayProvider();
        };
    }

    private function configureTwig(): void
    {
        $this['view.manager']->addPath(array(
            $this['root.path'] . 'views/',
        ));
    }

    protected function mountControllerProviders(): void
    {
    }
}
