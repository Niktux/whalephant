<?php

declare(strict_types = 1);

namespace Whalephant\Framework\Providers;

use Pimple\ServiceProviderInterface;
use Puzzle\Configuration;
use Pimple\Container;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $this->validatePuzzleConfiguration($container);
        $this->initializeTwigProvider($container);
    }

    private function initializeTwigProvider(Container $container): void
    {
        $container['twig'] = static function (Container $c) {
            return $c['twig.environment_factory']($c);
        };

        $container['twig.cache.path'] = static function (Container $c) {
            $cacheDirectory = $c['configuration']->read('twig/cache/directory', false);
            if($cacheDirectory !== false)
            {
                $cacheDirectory = $c['var.path'] . $cacheDirectory;
            }

            return $cacheDirectory;
        };

        $container['twig.environment_factory'] = $container->protect(function (Container $c) {
            return new Environment($c['twig.loader'], [
                'charset' => $c['charset'],
                'debug' => $c['debug'],
                'strict_variables' => $c['debug'],
                'cache' => $c['twig.cache.path'],
                'auto_reload' => $c['configuration']->read('twig/developmentMode', false),
            ]);
        });

        $container['twig.loader'] = static function (Container $c) {
            return new FilesystemLoader($c['twig.paths']);
        };

        $container['twig.paths'] = static function(Container $c) {
            return [
                $c['root.path'] . 'views/'
            ];
        };
    }

    private function validatePuzzleConfiguration(Container $container): void
    {
        if(! isset($container['configuration']) || ! $container['configuration'] instanceof Configuration)
        {
            throw new \LogicException(__CLASS__ . ' requires an instance of Puzzle\Configuration (as key "configuration").');
        }
    }
}
