<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class GD implements Extension
{
    public function getName(): string
    {
        return "gd";
    }
    
    public function getRecipe(): Recipe
    {
        return (new Recipe())
            ->addPackage('libfreetype6-dev ')
            ->addPackage('libjpeg62-turbo-dev')
            ->addPackage('libpng12-dev')
            ->addExtensionToInstall('gd')
        ;
    }
}
