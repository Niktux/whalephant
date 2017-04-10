<?php

namespace Whalephant\Model;

class Php
{
    public
        $version,
        $variant;
    
    public function __construct($version = '7', $variant = 'cli')
    {
        $this->version= $version;
        $this->variant = $variant;
    }
}
