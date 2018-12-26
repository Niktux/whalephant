<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;
use Whalephant\Model\ValueObjects\SystemPackage;

class Intl implements Extension
{
    public function getName(): string
    {
        return "intl";
    }

    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->addSystemPackage(new SystemPackage('libicu-dev'))
            ->addPeclExtension(
                new PeclExtension('intl', $version, PeclInstallationMode::docker())
            )
        ;
    }
}
