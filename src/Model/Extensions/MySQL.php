<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class MySQL implements Extension
{
    public function getName(): string
    {
        return "mysql";
    }
    
    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->addExtensionToInstall('pdo_mysql')
        ;
    }
}
