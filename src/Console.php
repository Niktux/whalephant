<?php

namespace Whalephant;

use Pimple\Container;

class Console
{
    private
        $app;

    public function __construct(Container $container)
    {
        $this->app = new Console\Application();

        $this->app->add(new Console\Generate($container['generator']));
        $this->app->add(new Console\Extensions($container['extension.provider']));
        $this->app->add(new Console\Version());
    }

    public function run(): void
    {
        $this->app->run();
    }
}
