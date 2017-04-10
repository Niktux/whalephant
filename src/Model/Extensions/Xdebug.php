<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

class Xdebug extends AbstractExtension
{
    public function getName(): ?string
    {
        return "xdebug";
    }
    
    public function getPeclInstall(): ?string
    {
        return "xdebug";
    }
}
