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
        $recipe = new Recipe();

        if($version !== null && $version[0] === '5')
        {
            $recipe
                ->maximumPhp('5.6')
                ->addMacroNameForIncludingSpecificCode('meminfo5');
        }
        else
        {
            $recipe->minimumPhp('7.0.0')
                ->addMacroNameForIncludingSpecificCode('meminfo7');
        }

        return $recipe
            ->addPackage('git')
            ->addPackage('unzip')
            ->addIniDirective('extension=meminfo.so')
        ;
    }
}
