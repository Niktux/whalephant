<?php

namespace Whalephant;

use Puzzle\Configuration;
use Pimple\Container;

class Console
{
    private
        $app,
        $configuration;

    public function __construct(Container $container)
    {
        $this->configuration = $container['configuration'];

        $this->app = new Console\Application();

        $this->app->add(new Console\Generate($container['generator']));
    }

    public function run(): void
    {
        $this->app->run();
    }
}
