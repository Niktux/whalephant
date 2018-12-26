<?php

declare(strict_types = 1);

namespace Whalephant\Model\Collections;

use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\PeclInstallationMode;

class PeclExtensionCollection implements \IteratorAggregate, \Countable
{
    private
        $extensions;

    public function __construct(iterable $extensions = [])
    {
        $this->extensions = [];

        foreach($extensions as $extension)
        {
            if($extension instanceof PeclExtension)
            {
                $this->add($extension);
            }
        }
    }

    public function add(PeclExtension $extension): self
    {
        $this->extensions[] = $extension;

        return $this;
    }

    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->extensions);
    }

    public function count(): int
    {
        return count($this->extensions);
    }

    public function installByPecl(): self
    {
        $collection = new self();

        foreach($this->extensions as $extension)
        {
            if($extension->install()->equals(PeclInstallationMode::pecl()))
            {
                $collection->add($extension);
            }
        }

        return $collection;
    }

    public function installByDocker(): self
    {
        $collection = new self();

        foreach($this->extensions as $extension)
        {
            if($extension->install()->equals(PeclInstallationMode::docker()))
            {
                $collection->add($extension);
            }
        }

        return $collection;
    }

    public function configure(): self
    {
        $collection = new self();

        foreach($this->extensions as $extension)
        {
            if($extension->configureOptions())
            {
                $collection->add($extension);
            }
        }

        return $collection;
    }

    public function enable(): self
    {
        $collection = new self();

        foreach($this->extensions as $extension)
        {
            if($extension->enable())
            {
                $collection->add($extension);
            }
        }

        return $collection;
    }

    public function has(PeclExtension $searched): bool
    {
        foreach($this->extensions as $extension)
        {
            if($extension->equals($searched))
            {
                return true;
            }
        }

        return false;
    }

    public function unique(): self
    {
        $collection = new self();

        foreach($this->extensions as $extension)
        {
            if($collection->has($extension) === false)
            {
                $collection->add($extension);
            }
        }

        return $collection;
    }
}
