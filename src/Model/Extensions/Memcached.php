<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\VersionableExtension;

class Memcached implements Extension
{
    use VersionableExtension;
    
    public function getName(): string
    {
        return "memcached";
    }
    
    public function getRecipe(?string $version = null): Recipe
    {
        $recipe = new Recipe();
        
        if($version !== null && substr($version, 0, 1) === '2')
        {
            $recipe->maximumPhp('5.6');
        }
        
        if($version !== null && substr($version, 0, 1) === '3')
        {
            $recipe->minimumPhp('7.0.0');
        }
        
        return $recipe
            ->addPackage('libmemcached-dev')
            ->addPackage('zlib1g-dev')
            ->addPeclPackageToInstall($this->versionedName('memcached', $version))
            ->addPeclPackageToEnable('memcached')
        ;
    }
}
