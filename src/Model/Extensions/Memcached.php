<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Php;
use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;
use Whalephant\Model\ValueObjects\SystemPackage;

class Memcached implements Extension
{
    public function getName(): string
    {
        return "memcached";
    }
    
    public function getRecipe(Php $php, ?string $version = null): Recipe
    {
        $recipe = new Recipe();
        
        if($version !== null && $version[0] === '2')
        {
            $recipe->maximumPhp('5.6');
        }
        
        if($version !== null && $version[0] === '3')
        {
            $recipe->minimumPhp('7.0.0');
        }
        
        return $recipe
            ->addSystemPackage(new SystemPackage('libmemcached-dev'))
            ->addSystemPackage(new SystemPackage('zlib1g-dev'))
            ->addPeclExtension(
                new PeclExtension('memcached', $version, PeclInstallationMode::pecl())
            )
        ;
    }
}
