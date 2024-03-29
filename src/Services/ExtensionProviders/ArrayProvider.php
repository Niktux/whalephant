<?php

namespace Whalephant\Services\ExtensionProviders;

use Whalephant\Model\Extensions\Intl;
use Whalephant\Services\ExtensionProvider;
use Whalephant\Model\Extension;
use Whalephant\Model\Extensions\Xdebug;
use Whalephant\Model\Extensions\Amqp;
use Whalephant\Model\Extensions\Zip;
use Whalephant\Model\Extensions\Meminfo;
use Whalephant\Model\Extensions\Redis;
use Whalephant\Model\Extensions\Memcached;
use Whalephant\Model\Extensions\GD;
use Whalephant\Model\Extensions\MySQL;
use Whalephant\Model\Extensions\PdoPostgresql;
use Whalephant\Model\Extensions\Postgresql;
use Whalephant\Model\Extensions\Calendar;
use Whalephant\Model\Extensions\Imagick;

class ArrayProvider implements ExtensionProvider
{
    private
        $extensions;

    public function __construct()
    {
        $this->extensions = [];
        $this->init();
    }

    private function init(): void
    {
        $this
            ->register(new Xdebug())
            ->register(new Calendar())
            ->register(new Amqp())
            ->register(new Zip())
            ->register(new Meminfo())
            ->register(new Redis())
            ->register(new MySQL())
            ->register(new PdoPostgresql())
            ->register(new Postgresql())
            ->register(new GD())
            ->register(new Imagick())
            ->register(new Intl())
        ;
    }

    private function register(Extension $e): self
    {
        $this->extensions[strtolower($e->name())] = $e;

        return $this;
    }

    public function exists(string $name): bool
    {
        return isset($this->extensions[strtolower($name)]);
    }

    public function get(string $name): ?Extension
    {
        if(! $this->exists($name))
        {
            return null;
        }

        return $this->extensions[$name];
    }

    public function listNames(): iterable
    {
        $names = array_keys($this->extensions);

        sort($names);

        return $names;
    }
}
