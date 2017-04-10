<?php

declare(strict_types = 1);

namespace Whalephant\Model;

class Project
{
    private
        $name,
        $systemPackages;
    
    public function __construct(string $name, array $systemPackages = [])
    {
        $this->name = $name;
        $this->systemPackages = $systemPackages;
    }
    
    public function addSystemPackage(string $package): self
    {
        $this->systemPackages[] = $package;
        
        return $this;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getSystemPackages(): array
    {
        return $this->systemPackages;
    }
}
