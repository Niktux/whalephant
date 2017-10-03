<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class Imagick implements Extension
{
    public function getName(): string
    {
        return "imagick";
    }

    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->addPackage('libmagickwand-dev')
            ->addPeclPackageToInstall('imagick')
            ->addPeclPackageToEnable('imagick')
        ;
    }
}
