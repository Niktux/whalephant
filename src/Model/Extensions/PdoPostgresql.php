<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Php;
use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;
use Whalephant\Model\ValueObjects\SystemPackage;

class PdoPostgresql implements Extension
{
    public function name(): string
    {
        return "pdo-postgresql";
    }

    public function recipe(Php $php, ?string $version = null): Recipe
    {
        return (new Recipe())
            ->addSystemPackage(new SystemPackage('libpq-dev'))
            ->addPeclExtension(
                new PeclExtension('pdo_pgsql', $version, PeclInstallationMode::docker())
            )
        ;
    }
}
