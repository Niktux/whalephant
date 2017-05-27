<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\VersionableExtension;

class Calendar implements Extension
{
    use VersionableExtension;

    public function getName(): string
    {
        return "calendar";
    }

    public function getRecipe(?string $version = null): Recipe
    {
        return (new Recipe())
            ->addExtensionToInstall('calendar')
        ;
    }
}
