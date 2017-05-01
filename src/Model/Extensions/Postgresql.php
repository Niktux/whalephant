<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class Postgresql implements Extension
{
    public function getName(): string
    {
        return "postgresql";
    }
    
    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->addPackage('libpq-dev')
            ->addExtensionToInstall('pdo_pgsql')
        ;
    }
}
