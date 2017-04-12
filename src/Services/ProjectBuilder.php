<?php

declare(strict_types = 1);

namespace Whalephant\Services;

use Puzzle\Configuration;
use Whalephant\Model\Php;
use Whalephant\Model\Project;

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
        $name = $this->config->readRequired('name');
        $project = new Project($name, $this->extractPhp());
        
        $extensions = $this->config->read('extensions', []);
        
        if(! is_iterable($extensions))
        {
            throw new \InvalidArgumentException("extensions must be iterable");
        }
        
        foreach($extensions as $extension)
        {
            if($this->extensionProvider->exists($extension))
            {
                $project->addExtension($this->extensionProvider->get($extension));
            }
        }
        
        return $project;
    }
    
    private function extractPhp(): Php
    {
        $version = (string) $this->config->read('php/version', '7');

        return new Php($version);
    }
}
