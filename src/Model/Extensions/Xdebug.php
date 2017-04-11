<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class XDebug implements Extension
{
    public function getRecipe(): Recipe
    {
        return (new Recipe())
            ->addPeclPackageToInstall('xdebug')
            ->addPeclPackageToEnable('xdebug')
        ;
    }
}
