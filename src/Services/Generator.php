<?php

declare(strict_types = 1);

namespace Whalephant\Services;

use League\Flysystem\Filesystem;

class Generator
{
    private
        $fs;
    
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }
}
