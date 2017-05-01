<?php

declare(strict_types = 1);

namespace Whalephant\Model;

class Php
{
    public
        $version,
        $major,
        $minor,
        $patch,
        $variant;
    
    public function __construct(string $version = '7', string $variant = 'cli')
    {
        list($this->major, $this->minor, $this->patch) = $this->extractDetailsFromVersion($version);
        $this->version= $version;
        $this->variant = $variant;
    }
    
    private function extractDetailsFromVersion(?string $version, bool $undefinedIsLatest = true): array
    {
        if(is_null($version))
        {
            return [0, 0, 0];
        }
        
        $parts = explode('.', $version);
        
        $major = 7;
        $minor = $undefinedIsLatest ? false : 0;
        $patch = $undefinedIsLatest ? false : 0;
        
        if(is_numeric($parts[0]))
        {
            $major = (int) $parts[0];
        }
        
        if(isset($parts[1]) && is_numeric($parts[1]))
        {
            $minor = (int) $parts[1];
        }

        if(isset($parts[2]) && is_numeric($parts[2]))
        {
            $patch = (int) $parts[2];
        }
        
        return [$major, $minor, $patch];
    }
    
    public function isCompatibleWith(?string $versionAtLeast = null, ?string $versionAtMost = null): bool
    {
        $atLeast = true;
        $atMost = true;
        
        if($versionAtLeast !== null)
        {
            $atLeast = $this->isGreaterOrEqualThan($versionAtLeast);
        }
        
        if($versionAtMost!== null)
        {
            $atMost = $this->isLowerOrEqualThan($versionAtMost);
        }
        
        return $atLeast && $atMost;
    }
        
    public function isGreaterOrEqualThan(?string $version): bool
    {
        list($majorAtLeast, $minorAtLeast, $patchAtLeast) = $this->extractDetailsFromVersion($version, false);
        
        if($this->major !== $majorAtLeast)
        {
            return $this->major > $majorAtLeast;
        }
        
        if($this->minor === false)
        {
            // false means latest => the highest possible version
            return true;
        }
        
        if($this->minor !== $minorAtLeast)
        {
            return $this->minor > $minorAtLeast;
        }
        
        // Here we have same major and same minor, let's compare patches
        if($this->patch === false)
        {
            // false means latest => the highest possible version
            return true;
        }
        
        return $this->patch >= $patchAtLeast;
    }
    
    public function isLowerOrEqualThan(?string $version): bool
    {
        list($majorAtMost, $minorAtMost, $patchAtMost) = $this->extractDetailsFromVersion($version);
        
        if($this->major !== $majorAtMost)
        {
            return $this->major < $majorAtMost;
        }
        
        // Same major
        
        if($minorAtMost === false)
        {
            // false means latest => the highest possible version
            return true;
        }
        
        if($this->minor !== $minorAtMost)
        {
            return $this->minor < $minorAtMost;
        }
        
        // Here we have same major and same minor, let's compare patches
        if($patchAtMost === false)
        {
            // false means latest => the highest possible version
            return true;
        }
        
        return $this->patch <= $patchAtMost;
    }
}
