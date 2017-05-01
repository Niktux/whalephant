<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\VersionableExtension;

class Redis implements Extension
{
    use VersionableExtension;
    
    public function getName(): string
    {
        return "redis";
    }
    
    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
        ->addPeclPackageToInstall($this->versionedName('redis', $version))
            ->addPeclPackageToEnable('redis')
        ;
    }
}
