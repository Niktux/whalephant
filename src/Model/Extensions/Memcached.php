<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class Memcached implements Extension
{
    public function getName(): string
    {
        return "memcached";
    }
    
    public function getRecipe(): Recipe
    {
        return (new Recipe())
            ->minimumPhp('7.0.0') // 3.x for php 7,  2.x for php 5
            ->addPackage('libmemcached-dev')
            ->addPackage('zlib1g-dev')
            ->addPeclPackageToInstall('memcached')
            ->addPeclPackageToEnable('memcached')
        ;
    }
}
