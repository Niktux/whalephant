<?php

namespace Whalephant\Model;

interface Extension
{
    public function getName(): string;
    public function getRecipe(): Recipe;
}
