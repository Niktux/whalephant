<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Extension;

abstract class AbstractExtension implements Extension
{
    public function getName(): ?string
    {
        return null;
    }
    
    public function getSystemPackages(): iterable
    {
        return [];
    }
    
    public function getPeclInstall(): ?string
    {
        return null;
    }
    
    public function macroBeforePeclInstall(): ?string
    {
        return null;
    }
}
