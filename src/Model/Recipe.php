<?php

declare(strict_types = 1);

namespace Whalephant\Model;

class Recipe
{
    private
        $requires,
        $packages,
        $macros,
        $pecl,
        $ini;
    
    public function __construct()
    {
        $this->requires = [
            'php' => [
                'min' => null,
                'max' => null,
            ],
        ];
        
        $this->packages = [];
        $this->macros = [];
        
        $this->pecl = [
            'install' => [],
            'enable' => [],
        ];
        
        $this->ini = [];
    }
    
    public function minimumPhp(string $version): self
    {
        $this->requires['php']['min'] = $version;
        
        return $this;
    }
    
    public function maximumPhp(string $version): self
    {
        $this->requires['php']['max'] = $version;
        
        return $this;
    }
    
    public function addPackage(string $packageName): self
    {
        $this->packages[] = $packageName;
        
        return $this;
    }
    
    public function addMacroNameForIncludingSpecificCode(string $macro): self
    {
        $this->macros[] = $macro;
        
        return $this;
    }
    
    public function addPeclPackageToInstall(string $package): self
    {
        $this->pecl['install'][] = $package;
        
        return $this;
    }
    
    public function addPeclPackageToEnable(string $package): self
    {
        $this->pecl['enable'][] = $package;
        
        return $this;
    }
    
    public function addIniDirective(string $line): self
    {
        $this->ini[] = $line;
        
        return $this;
    }
    
    public function mergeWith(Recipe $recipe): Recipe
    {
        $merged = clone $recipe;
        
        if($this->requires['php']['min'] !== null)
        {
            $php = new Php($this->requires['php']['min']);
            
            if($recipe->requires['php']['min'] === null
            || $php->isGreaterOrEqualThan($recipe->requires['php']['min']))
            {
                $merged->minimumPhp($php->version);
            }
        }
        
        if($this->requires['php']['max'] !== null)
        {
            $php = new Php($this->requires['php']['max']);
            
            if($recipe->requires['php']['max'] === null
            || $php->isLowerOrEqualThan($recipe->requires['php']['max']))
            {
                $merged->maximumPhp($php->version);
            }
        }
        
        foreach($this->packages as $package)
        {
            $merged->addPackage($package);
        }
        
        foreach($this->macros as $macro)
        {
            $merged->addMacroNameForIncludingSpecificCode($macro);
        }
        
        foreach($this->pecl['install'] as $package)
        {
            $merged->addPeclPackageToInstall($package);
        }
        
        foreach($this->pecl['enable'] as $package)
        {
            $merged->addPeclPackageToEnable($package);
        }
        
        foreach($this->ini as $line)
        {
            $merged->addIniDirective($line);
        }
        
        $merged->pack();
        
        return $merged;
    }
    
    public function pack(): self
    {
        $this->packages = array_unique($this->packages);
        $this->macros = array_unique($this->macros);
        $this->ini = array_unique($this->ini);

        $this->pecl['install'] = array_unique($this->pecl['install']);
        $this->pecl['enable'] = array_unique($this->pecl['enable']);
        
        return $this;
    }
    
    public function getPackages(): array
    {
        return $this->packages;
    }
    
    public function getMacros(): array
    {
        return $this->macros;
    }
    
    public function getIniDirectives(): array
    {
        return $this->ini;
    }
    
    public function getPeclPackagesToInstall(): array
    {
        return $this->pecl['install'];
    }
    
    public function getPeclPackagesToEnable(): array
    {
        return $this->pecl['enable'];
    }
    
    public function getMinimumPhp(): ?string
    {
        return $this->requires['php']['min'];
    }
    
    public function getMaximumPhp(): ?string
    {
        return $this->requires['php']['max'];
    }
}
