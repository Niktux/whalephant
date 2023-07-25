<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Php;
use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;

class Redis implements Extension
{
    public function name(): string
    {
        return "redis";
    }

    public function recipe(Php $php, ?string $version = null): Recipe
    {
        return (new Recipe())
            ->addPeclExtension(
                new PeclExtension('redis', $version, PeclInstallationMode::pecl())
            )
        ;
    }
}
