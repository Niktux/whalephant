<?php

declare(strict_types = 1);

namespace Whalephant\Model;

class Pecl
{
    private
        $extensions;
    
    public function __construct()
    {
        $this->extensions = [];
    }
    
    public function addExtension(Extension $extension): self
    {
        $this->extensions[] = $extension;
        
        return $this;
    }
    
    public function getExtensions()
    {
        return $this->extensions;
    }

    public function getMacroList(): array
    {
        return array_filter(array_map(function(Extension $e) {
            return $e->macroBeforePeclInstall();
        }, $this->extensions));
    }
    
    public function getInstallList(): array
    {
        return array_filter(array_map(function(Extension $e) {
            return $e->getPeclInstall();
        }, $this->extensions));
    }
    
    public function getEnableList(): array
    {
        return array_filter(array_map(function(Extension $e) {
            return $e->getName();
        }, $this->extensions));
    }
    
    public function getSystemPackages(): array
    {
        $packages = [];
        
        foreach($this->extensions as $extension)
        {
            $packages = array_merge($packages, $extension->getSystemPackages());
        }

        return array_filter(array_unique($packages));
    }
}
