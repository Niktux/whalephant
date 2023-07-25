<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Php;
use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;
use Whalephant\Model\ValueObjects\SpecificCode;
use Whalephant\Model\ValueObjects\SystemPackage;

class Amqp implements Extension
{
    public function name(): string
    {
        return "amqp";
    }
    
    public function recipe(Php $php, ?string $version = null): Recipe
    {
        if($version === null)
        {
            $version = '1.7.1';
        }

        return (new Recipe())
            ->needAutomake()
            ->addSystemPackage(new SystemPackage('librabbitmq-dev'))
            ->addPeclExtension(
                new PeclExtension('amqp', $version, PeclInstallationMode::pecl())
            )
        ;
    }
}
