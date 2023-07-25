<?php

declare(strict_types = 1);

namespace Whalephant\Framework\Providers\Silex;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Extension\DumpExtension;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\SecurityExtension;
use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Bridge\Twig\Extension\HttpKernelExtension;
use Symfony\Bridge\Twig\Extension\WebLinkExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Bridge\Twig\Extension\HttpKernelRuntime;
use Twig\Environment;
use Twig\Extension\CoreExtension;
use Twig\Extension\DebugExtension;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;

/**
 * Twig integration for Silex.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TwigServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app): void
    {
        $app['twig.options'] = [];
        $app['twig.path'] = [];
        $app['twig.templates'] = [];

        $app['twig.date.format'] = 'F j, Y H:i';
        $app['twig.date.interval_format'] = '%d days';
        $app['twig.date.timezone'] = null;

        $app['twig.number_format.decimals'] = 0;
        $app['twig.number_format.decimal_point'] = '.';
        $app['twig.number_format.thousands_separator'] = ',';

        $app['twig'] = static function ($app) {
            $twig = $app['twig.environment_factory']($app);
            $twig->addGlobal('app', $app);

            $coreExtension = $twig->getExtension(CoreExtension::class);

            $coreExtension->setDateFormat($app['twig.date.format'], $app['twig.date.interval_format']);

            if (null !== $app['twig.date.timezone']) {
                $coreExtension->setTimezone($app['twig.date.timezone']);
            }

            $coreExtension->setNumberFormat($app['twig.number_format.decimals'], $app['twig.number_format.decimal_point'], $app['twig.number_format.thousands_separator']);

            if ($app['debug']) {
                $twig->addExtension(new DebugExtension());
            }

            return $twig;
        };

        $app['twig.loader.filesystem'] = function ($app) {
            $loader = new FilesystemLoader();
            foreach (is_array($app['twig.path']) ? $app['twig.path'] : [$app['twig.path']] as $key => $val) {
                if (is_string($key)) {
                    $loader->addPath($key, $val);
                } else {
                    $loader->addPath($val);
                }
            }

            return $loader;
        };

        $app['twig.loader.array'] = static function ($app) {
            return new ArrayLoader($app['twig.templates']);
        };

        $app['twig.loader'] = static function ($app) {
            return new ChainLoader([
                $app['twig.loader.array'],
                $app['twig.loader.filesystem'],
            ]);
        };

        $app['twig.environment_factory'] = $app->protect(function ($app) {
            return new Environment($app['twig.loader'], array_replace([
                'charset' => $app['charset'],
                'debug' => $app['debug'],
                'strict_variables' => $app['debug'],
            ], $app['twig.options']));
        });

        $app['twig.runtime.httpkernel'] = function ($app) {
            return new HttpKernelRuntime($app['fragment.handler']);
        };

        $app['twig.runtimes'] = function ($app) {
            return [
                HttpKernelRuntime::class => 'twig.runtime.httpkernel',
            ];
        };
    }
}
