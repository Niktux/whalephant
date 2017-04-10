<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

class Zip extends AbstractExtension
{
    public function getName(): ?string
    {
        return null;
    }
    
    public function getSystemPackages(): iterable
    {
        return [];
    }
}