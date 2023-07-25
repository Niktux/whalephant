<?php

declare(strict_types = 1);

namespace Whalephant\Model\ValueObjects;

use Puzzle\Pieces\ConvertibleToString;

final class PeclInstallationMode implements ConvertibleToString
{
    private string
        $value;

    public function __construct(string $value)
    {
        $value = strtolower($value);
        $allowed = ['pecl', 'docker', 'none'];

        if(! in_array($value, $allowed))
        {
            throw new \LogicException("Invalid PECL installation mode : $value");
        }

        $this->value = $value;
    }

    public static function pecl(): self
    {
        return new self('pecl');
    }

    public static function docker(): self
    {
        return new self('docker');
    }

    public static function none(): self
    {
        return new self('none');
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $mode): bool
    {
        return $this->value === $mode->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
