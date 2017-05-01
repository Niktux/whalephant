<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class Meminfo implements Extension
{
    public function getName(): string
    {
        return "meminfo";
    }
    
    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->maximumPhp('5.6')
            ->addPackage('php5-dev')
            ->addPackage('git')
            ->addPackage('unzip')
            ->addMacroNameForIncludingSpecificCode('meminfo')
            ->addIniDirective('extension=meminfo.so')
        ;
    }
}
