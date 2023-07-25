<?php

declare(strict_types = 1);

namespace Whalephant\Services;

use Puzzle\Configuration;
use Whalephant\Model\Php;
use Whalephant\Model\Project;
use Whalephant\Model\Recipe;

class ProjectBuilder
{
    private const EXTENSION_SEPARATOR = ':';
    
    private ExtensionProvider
        $extensionProvider;
    
    public function __construct(ExtensionProvider $extensionProvider)
    {
        $this->extensionProvider = $extensionProvider;
    }
    
    public function build(Configuration $config): Project
    {
        $project = new Project(
            $config->readRequired('name'),
            $this->extractPhp($config)
        );
        
        foreach($this->extractExtensions($config) as $extensionInfo)
        {
            $project->addExtension($extensionInfo['extension'], $extensionInfo['version']);
        }
        
        $recipe = $this->extractIniDirectives($config);
        if($recipe instanceof Recipe)
        {
            $project->addRecipe($recipe);
        }
        
        return $project;
    }
    
    private function extractPhp(Configuration $config): Php
    {
        $version = (string) $config->read('php/version', '7');

        return new Php($version);
    }
    
    private function extractExtensions(Configuration $config): iterable
    {
        $result = [];
        
        $extensions = $config->read('extensions', []);
        
        if(! is_iterable($extensions))
        {
            throw new \InvalidArgumentException("extensions must be iterable");
        }
        
        foreach($extensions as $extension)
        {
            $version = null;
            
            if(stripos($extension, self::EXTENSION_SEPARATOR) !== false)
            {
                list($extension, $version) = explode(self::EXTENSION_SEPARATOR, $extension);
            }
            
            if(! $this->extensionProvider->exists($extension))
            {
                throw new \InvalidArgumentException("Unknown extension $extension");
            }
            
            $result[] = [
                'extension' => $this->extensionProvider->get($extension),
                'version' => $version,
            ];
        }
        
        return $result;
    }
    
    private function extractIniDirectives(Configuration $config): ?Recipe
    {
        $recipe = null;

        $iniLines = $config->read('ini', []);
        
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
