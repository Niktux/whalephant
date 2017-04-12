<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;

class Amqp implements Extension
{
    public function getName(): string
    {
        return "amqp";
    }
    
    public function getRecipe(): Recipe
    {
        return (new Recipe())
            ->addMacroNameForIncludingSpecificCode('amqp')
            ->addPeclPackageToInstall('amqp-1.7.0')
            ->addPeclPackageToEnable('amqp')
        ;
    }
}
