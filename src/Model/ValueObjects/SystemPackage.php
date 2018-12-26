<?php

declare(strict_types = 1);

namespace Whalephant\Model\ValueObjects;

use Puzzle\Pieces\ConvertibleToString;

final class SystemPackage implements ConvertibleToString
{
    private
        $package;

    public function __construct(string $package)
    {
        $this->package = $package;
    }

    public function value(): string
    {
        return $this->package;
    }

    public function equals(self $package): bool
    {
        return $this->package === $package->value();
    }

    public function __toString(): string
    {
        return $this->package;
    }
}
