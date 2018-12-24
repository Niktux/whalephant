<?php

declare(strict_types = 1);

namespace Whalephant\Model;

use Whalephant\Model\Collections\PeclExtensionCollection;
use Whalephant\Model\ValueObjects\PeclExtension;

class Recipe
{
    private
        $requires,
        $needAutomake,
        $packages,
        $macros,
        $peclExtensions,
        $ini;
    
    public function __construct()
    {
        $this->requires = [
            'php' => [
                'min' => null,
                'max' => null,
            ],
        ];
        
        $this->needAutomake = false;
        $this->packages = [];
        $this->macros = [];
        $this->peclExtensions = new PeclExtensionCollection();

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
    
    public function needAutomake(): self
    {
        $this->needAutomake = true;
        
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

    public function addPeclExtension(PeclExtension $extension): self
    {
        $this->peclExtensions->add($extension);

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

        foreach($this->peclExtensions as $extension)
        {
            $merged->addPeclExtension($extension);
        }
        
        foreach($this->ini as $line)
        {
            $merged->addIniDirective($line);
        }
        
        if($this->needAutomake)
        {
            $merged->needAutomake();
        }
        
        $merged->pack();
        
        return $merged;
    }
    
    public function pack(): self
    {
        $this->packages = array_unique($this->packages);
        $this->macros = array_unique($this->macros);
        $this->ini = array_unique($this->ini);

        $this->peclExtensions = $this->peclExtensions->unique();

        return $this;
    }
    
    public function getAutomakeNeeded(): bool
    {
        return $this->needAutomake;
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

    public function peclExtensions(): PeclExtensionCollection
    {
        return $this->peclExtensions;
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
