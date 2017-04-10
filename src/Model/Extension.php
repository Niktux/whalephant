<?php

namespace Whalephant\Model;

interface Extension
{
    // TODO PHP Version compatibility check
    
    public function getName(): ?string;
    
    public function getSystemPackages(): iterable;
    
    public function getPeclInstall(): ?string;
    
    public function macroBeforePeclInstall(): ?string;
}
