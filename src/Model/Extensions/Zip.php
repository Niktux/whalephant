<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\SystemPackage;

class Zip implements Extension
{
    public function getName(): string
    {
        return "zip";
    }
    
    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->addSystemPackage(new SystemPackage('zlib1g-dev'))
            ->addMacroNameForIncludingSpecificCode('zip')
        ;
    }
}
