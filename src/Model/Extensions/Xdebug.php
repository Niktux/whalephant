<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class Xdebug implements Extension
{
    public function getName(): string
    {
        return "xdebug";
    }
    
    public function getRecipe(): Recipe
    {
        return (new Recipe())
            ->addPeclPackageToInstall('xdebug')
            ->addPeclPackageToEnable('xdebug')
        ;
    }
}
