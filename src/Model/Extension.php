<?php

namespace Whalephant\Model;

interface Extension
{
    public function name(): string;
    public function recipe(Php $php, ?string $version = null): Recipe;
}
