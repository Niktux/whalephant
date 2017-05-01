<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\VersionableExtension;

class Amqp implements Extension
{
    use VersionableExtension;
    
    public function getName(): string
    {
        return "amqp";
    }
    
    public function getRecipe(?string $version = null): Recipe
    {
        if($version === null)
        {
            $version = '1.7.1';
        }
        
        return (new Recipe())
            ->needAutomake()
            ->addMacroNameForIncludingSpecificCode('amqp')
            ->addPeclPackageToInstall($this->versionedName('amqp', $version))
            ->addPeclPackageToEnable('amqp')
        ;
    }
}
