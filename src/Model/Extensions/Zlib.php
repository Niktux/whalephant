<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

class Zlib extends AbstractExtension
{
    public function getSystemPackages(): iterable
    {
        return ['zlib1g-dev'];
    }
    
    public function macroBeforePeclInstall(): ?string
    {
        return 'zlib';
    }
}
