<?php

declare(strict_types = 1);

namespace Whalephant\Services;

use Puzzle\Configuration;
use Whalephant\Model\Php;
use Whalephant\Model\Project;
use Whalephant\Model\Recipe;

class ProjectBuilder
{
    private
        $config,
        $extensionProvider;
    
    public function __construct(Configuration $config, ExtensionProvider $extensionProvider)
    {
        $this->config = $config;
        $this->extensionProvider = $extensionProvider;
    }
    
    public function build(): Project
    {
        $project = new Project(
            $this->config->readRequired('name'),
            $this->extractPhp()
        );
        
        foreach($this->extractExtensions() as $extension)
        {
            $project->addExtension($extension);
        }
        
        $recipe = $this->extractIniDirectives();
        if($recipe instanceof Recipe)
        {
            $project->addRecipe($recipe);
        }
        
        return $project;
    }
    
    private function extractPhp(): Php
    {
        $version = (string) $this->config->read('php/version', '7');

        return new Php($version);
    }
    
    private function extractExtensions(): iterable
    {
        $result = [];
        
        $extensions = $this->config->read('extensions', []);
        
        if(! is_iterable($extensions))
        {
            throw new \InvalidArgumentException("extensions must be iterable");
        }
        
        foreach($extensions as $extension)
        {
            if($this->extensionProvider->exists($extension))
            {
                $result[] = $this->extensionProvider->get($extension);
            }
        }
        
        return $result;
    }
    
    private function extractIniDirectives(): ?Recipe
    {
        $recipe = null;

        $iniLines = $this->config->read('ini', []);
        
        if(! is_iterable($iniLines))
        {
            throw new \InvalidArgumentException("INI directives must be iterable");
        }
        
        if(! empty($iniLines))
        {
            $recipe = new Recipe();
            
            foreach($iniLines as $line)
            {
                $recipe->addIniDirective($line);
            }
        }
        
        return $recipe;
    }
}
