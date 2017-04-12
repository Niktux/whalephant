<?php

namespace Whalephant\Services\ExtensionProviders;

use Whalephant\Services\ExtensionProvider;
use Whalephant\Model\Extension;
use Whalephant\Model\Extensions\Xdebug;
use Whalephant\Model\Extensions\Amqp;
use Whalephant\Model\Extensions\Zlib;
use Whalephant\Model\Extensions\Meminfo;

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
            ->register(new Amqp())
            ->register(new Zlib())
            ->register(new Meminfo())
        ;
    }
    
    private function register(Extension $e): self
    {
        $this->extensions[$e->getName()] = $e;
        
        return $this;
    }
    
    public function exists(string $name): bool
    {
        return isset($this->extensions[$name]);
    }
    
    public function get(string $name): ?Extension
    {
        if(! $this->exists($name))
        {
            return null;
        }
        
        return $this->extensions[$name];
    }
}
