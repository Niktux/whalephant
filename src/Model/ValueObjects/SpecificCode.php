<?php

declare(strict_types = 1);

namespace Whalephant\Model\ValueObjects;

final readonly class SpecificCode
{
    private string
        $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function code(): string
    {
        return $this->code;
    }
}
