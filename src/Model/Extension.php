<?php

namespace Whalephant\Model;

interface Extension
{
    public function getName(): string;
    public function getRecipe(Php $php, ?string $version = null): Recipe;
}
