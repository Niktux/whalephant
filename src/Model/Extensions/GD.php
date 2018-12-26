<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;
use Whalephant\Model\ValueObjects\SystemPackage;

class GD implements Extension
{
    public function getName(): string
    {
        return "gd";
    }
    
    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->addSystemPackage(new SystemPackage('libfreetype6-dev '))
            ->addSystemPackage(new SystemPackage('libjpeg62-turbo-dev'))
            ->addSystemPackage(new SystemPackage('libpng-dev'))
            ->addPeclExtension(
                new PeclExtension('gd', $version, PeclInstallationMode::docker(), '--with-jpeg-dir --with-png-dir')
            )
        ;
    }
}
