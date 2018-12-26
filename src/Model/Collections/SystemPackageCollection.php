<?php

declare(strict_types = 1);

namespace Whalephant\Model\Collections;

use Whalephant\Model\ValueObjects\SystemPackage;

class SystemPackageCollection implements \IteratorAggregate, \Countable
{
    private
        $packages;

    public function __construct(iterable $packages = [])
    {
        $this->packages = [];

        foreach($packages as $package)
        {
            if($package instanceof SystemPackage)
            {
                $this->add($package);
            }
        }
    }

    public function add(SystemPackage $package): self
    {
        $this->packages[] = $package;

        return $this;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->packages);
    }

    public function count(): int
    {
        return count($this->packages);
    }

    public function has(SystemPackage $searched): bool
    {
        foreach($this->packages as $package)
        {
            if($package->equals($searched))
            {
                return true;
            }
        }

        return false;
    }

    public function unique(): self
    {
        $collection = new self();

        foreach($this->packages as $package)
        {
            if($collection->has($package) === false)
            {
                $collection->add($package);
            }
        }

        return $collection;
    }

    public function toArrayOfStrings(): array
    {
        $result = [];

        foreach($this->packages as $package)
        {
            $result[] = (string) $package;
        }

        return $result;
    }
}

