<?php

namespace Whalephant\Services;

use Whalephant\Model\Extension;

interface ExtensionProvider
{
    public function exists(string $name): bool;
    public function get(string $name): ?Extension;
}
