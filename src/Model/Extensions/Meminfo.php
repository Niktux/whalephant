<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

class Meminfo extends AbstractExtension
{
    public function getSystemPackages(): iterable
    {
        return ['php5-dev', 'git', 'unzip'];
    }
    
    public function macroBeforePeclInstall(): ?string
    {
        return 'meminfo';
    }
}
