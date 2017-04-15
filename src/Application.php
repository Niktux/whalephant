<?php

namespace Whalephant;

use Silex\Provider\SessionServiceProvider;
use Onyx\Providers;
use Whalephant\Services\Generator;

class Application extends \Onyx\Application
{
    const
        WHALEPHANT_FILENAME = 'whalephant.yml',
        VERSION = '0.2.0';
    
    protected function registerProviders(): void
    {
        $this->register(new SessionServiceProvider());
        $this->register(new Providers\Twig());
    }

    protected function initializeServices(): void
    {
        $this->configureTwig();
        
        $this['generator'] = function() {
            return new Generator($this['twig']);
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
