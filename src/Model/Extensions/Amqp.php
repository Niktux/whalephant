<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

class Amqp extends AbstractExtension
{
    public function getName(): ?string
    {
        return "amqp";
    }
    
    public function getPeclInstall(): ?string
    {
        return "amqp-1.7.0";
    }
    
    public function macroBeforePeclInstall(): ?string
    {
        return 'amqp';
    }
}
