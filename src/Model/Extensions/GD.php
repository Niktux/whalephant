<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Php;
use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;
use Whalephant\Model\ValueObjects\SystemPackage;

class GD implements Extension
{
    public function name(): string
    {
        return "gd";
    }
    
    public function recipe(Php $php, ?string $version = null): Recipe
    {
        $recipe = (new Recipe())
            ->addSystemPackage(new SystemPackage('libfreetype6-dev '))
            ->addSystemPackage(new SystemPackage('libjpeg62-turbo-dev'))
            ->addSystemPackage(new SystemPackage('libpng-dev'));

        if($php->isGreaterOrEqualThan('7.4'))
        {
            $recipe
                ->defineMinimumPhp('7.4')
                ->addPeclExtension(
                    new PeclExtension('gd', $version, PeclInstallationMode::docker(), '--with-jpeg')
            );
        }
        else
        {
            $recipe
                ->defineMaximumPhp('7.3')
                ->addPeclExtension(
                    new PeclExtension('gd', $version, PeclInstallationMode::docker(), '--with-jpeg-dir --with-png-dir')
            );
        }

        return $recipe;
    }
}
