<?php

declare(strict_types = 1);

namespace Whalephant\Model\Collections;

use Whalephant\Model\ValueObjects\SpecificCode;

class SpecificCodeCollection implements \IteratorAggregate, \Countable
{
    private array
        $codes;

    public function __construct(iterable $codes = [])
    {
        $this->codes = [];

        foreach($codes as $code)
        {
            if($code instanceof SpecificCode)
            {
                $this->add($code);
            }
        }
    }

    public function add(SpecificCode $code): void
    {
        $this->codes[] = $code;
    }

    /**
     * @return SpecificCode[]
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->codes);
    }

    public function count(): int
    {
        return count($this->codes);
    }


    public function has(SpecificCode $searched): bool
    {
        foreach($this->codes as $code)
        {
            if($code->equals($searched))
            {
                return true;
            }
        }

        return false;
    }

    public function unique(): self
    {
        $collection = new self();

        foreach($this->codes as $code)
        {
            if($collection->has($code) === false)
            {
                $collection->add($code);
            }
        }

        return $collection;
    }
}
