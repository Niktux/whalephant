<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;

class Redis implements Extension
{
    public function getName(): string
    {
        return "redis";
    }

    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->addPeclExtension(
                new PeclExtension('redis', $version, PeclInstallationMode::pecl())
            )
        ;
    }
}
