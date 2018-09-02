<?php

declare(strict_types = 1);

namespace Whalephant\Model;

trait VersionableExtension
{
    private function versionedName(string $name, ?string $version): string
    {
        return $name . ($version ? "-$version": '');
    }
}
