<?php

namespace Whalephant;

use Silex\Provider\SessionServiceProvider;
use Onyx\Providers;

class Application extends \Onyx\Application
{
    const
        VERSION = '0.1';
    
    protected function registerProviders(): void
    {
        $this->register(new SessionServiceProvider());
        $this->register(new Providers\Monolog([
            // insert your loggers here
        ]));
        $this->register(new Providers\Twig());
    }

    protected function initializeServices(): void
    {
        $this->configureTwig();
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
