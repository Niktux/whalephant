<?php

declare(strict_types = 1);

namespace Whalephant\Model;

use Whalephant\Model\Collections\SpecificCodeCollection;
use Whalephant\Model\Collections\PeclExtensionCollection;
use Whalephant\Model\ValueObjects\SpecificCode;
use Whalephant\Model\ValueObjects\PeclExtension;
use Whalephant\Model\ValueObjects\SystemPackage;
use Whalephant\Model\Collections\SystemPackageCollection;

class Recipe
{
    private array
        $requires;
    private bool
        $needAutomake;
    private SpecificCodeCollection
        $specificCodes;
    private SystemPackageCollection
        $systemPackages;
    private PeclExtensionCollection
        $peclExtensions;
    private array
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
        $this->specificCodes = new SpecificCodeCollection();
        $this->systemPackages = new SystemPackageCollection();
        $this->peclExtensions = new PeclExtensionCollection();

        $this->ini = [];
    }

    public function defineMinimumPhp(string $version): self
    {
        $this->requires['php']['min'] = $version;
        
        return $this;
    }
    
    public function defineMaximumPhp(string $version): self
    {
        $this->requires['php']['max'] = $version;
        
        return $this;
    }
    
    public function needAutomake(): self
    {
        $this->needAutomake = true;
        
        return $this;
    }
    
    public function addSystemPackage(SystemPackage $package): self
    {
        $this->systemPackages->add($package);
        
        return $this;
    }
    
    public function addSpecificCode(SpecificCode $code): self
    {
        $this->specificCodes->add($code);

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
                $merged->defineMinimumPhp($php->version);
            }
        }
        
        if($this->requires['php']['max'] !== null)
        {
            $php = new Php($this->requires['php']['max']);
            
            if($recipe->requires['php']['max'] === null
            || $php->isLowerOrEqualThan($recipe->requires['php']['max']))
            {
                $merged->defineMaximumPhp($php->version);
            }
        }
        
        foreach($this->systemPackages as $package)
        {
            $merged->addSystemPackage($package);
        }
        
        foreach($this->specificCodes as $macro)
        {
            $merged->addSpecificCode($macro);
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
        $this->systemPackages = $this->systemPackages->unique();
        $this->specificCodes = $this->specificCodes->unique();
        $this->ini = array_unique($this->ini);

        $this->peclExtensions = $this->peclExtensions->unique();

        return $this;
    }
    
    public function automakeNeeded(): bool
    {
        return $this->needAutomake;
    }
    
    public function systemPackages(): SystemPackageCollection
    {
        return $this->systemPackages;
    }
    
    public function specificCodes(): SpecificCodeCollection
    {
        return $this->specificCodes;
    }
    
    public function iniDirectives(): array
    {
        return $this->ini;
    }

    public function peclExtensions(): PeclExtensionCollection
    {
        return $this->peclExtensions;
    }

    public function minimumPhp(): ?string
    {
        return $this->requires['php']['min'];
    }
    
    public function maximumPhp(): ?string
    {
        return $this->requires['php']['max'];
    }
}
