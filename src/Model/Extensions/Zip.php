<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class Zip implements Extension
{
    public function getName(): string
    {
        return "zip";
    }
    
    public function getRecipe(): Recipe
    {
        return (new Recipe())
            ->addPackage('zlib1g-dev')
            ->addMacroNameForIncludingSpecificCode('zip')
        ;
    }
}
