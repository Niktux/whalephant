<?php

declare(strict_types = 1);

namespace Whalephant\Model;

class Project
{
    private
        $name,
        $recipes,
        $extensions,
        $php;
    
    public function __construct(string $name, Php $php)
    {
        $this->name = $name;
        $this->recipes = [];
        $this->extensions = [];
        $this->php = $php;
    }
    
    public function addRecipe(Recipe $recipe): self
    {
        $this->recipes[] = $recipe;
        
        return $this;
    }
    
    public function addExtension(Extension $extension, ?string $version = null): self
    {
        $this->extensions[] = [
            'extension' => $extension,
            'version' => $version,
        ];
        
        return $this;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getRecipe(): Recipe
    {
        $merged = new Recipe();
        
        foreach($this->recipes as $recipe)
        {
            $merged = $merged->mergeWith($recipe);
        }
            
        foreach($this->extensions as $extensionInfo)
        {
            $extension = $extensionInfo['extension'];
            $merged = $merged->mergeWith($extension->getRecipe($this->php, $extensionInfo['version']));
        }
        
        // TODO remove me
        $merged->pack();
        
        $this->checkPhpRequirements($merged);
        
        return $merged;
    }
    
    private function checkPhpRequirements(Recipe $recipe): void
    {
        if(! $this->php->isCompatibleWith($recipe->getMinimumPhp(), $recipe->getMaximumPhp()))
        {
            $min = $recipe->getMinimumPhp();
            $max = $recipe->getMaximumPhp();
            
            throw new \InvalidArgumentException(sprintf(
                "PHP %s is incompatible with requirements (%s)",
                $this->php->version,
                implode(", ", array_filter([$min ? ">= $min" : "", $max ? "<= $max": ""]))
            ));
        }
    }
    
    public function getPhp(): Php
    {
        return $this->php;
    }
}
