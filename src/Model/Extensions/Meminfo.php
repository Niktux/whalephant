<?php

declare(strict_types = 1);

namespace Whalephant\Model\Extensions;

use Whalephant\Model\Php;
use Whalephant\Model\Recipe;
use Whalephant\Model\Extension;
use Whalephant\Model\ValueObjects\SpecificCode;
use Whalephant\Model\ValueObjects\SystemPackage;

class Meminfo implements Extension
{
    public function name(): string
    {
        return "meminfo";
    }
    
    public function recipe(Php $php, ?string $version = null): Recipe
    {
        $recipe = new Recipe();

        if($version !== null && $version[0] === '5')
        {
            throw new \InvalidArgumentException("Meminfo for php 5.x is not supported anymore. Please use previous versions of Whalephant");
        }

        return $recipe->defineMinimumPhp('7.0.0')
            ->addSpecificCode($this->specificCodeForV7())
            ->addSystemPackage(new SystemPackage('git'))
            ->addSystemPackage(new SystemPackage('unzip'))
            ->addIniDirective('extension=meminfo.so')
        ;
    }

    private function specificCodeForV7(): SpecificCode
    {
        return new SpecificCode(<<<BASH
RUN git clone https://github.com/BitOne/php-meminfo.git && \
    cd php-meminfo/extension && \
    phpize && \
    ./configure --enable-meminfo && \
    make && \
    make install

RUN cd /php-meminfo/analyzer && \
    curl -sS https://getcomposer.org/installer | php && \
    php composer.phar update
BASH
);
    }
}
