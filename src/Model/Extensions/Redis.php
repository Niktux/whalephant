<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class Redis implements Extension
{
    public function getName(): string
    {
        return "redis";
    }
    
    public function getRecipe(): Recipe
    {
        return (new Recipe())
            ->addPeclPackageToInstall('redis')
            ->addPeclPackageToEnable('redis')
        ;
    }
}
