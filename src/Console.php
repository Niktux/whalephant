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
    }

    public function run(): void
    {
        $this->app->run();
    }
}
