<?php

declare(strict_types = 1);

namespace Whalephant\Model;

use PHPUnit\Framework\TestCase;

class PhpTest extends TestCase
{
    /**
     * @dataProvider providerTestGreaterOrEqualThan
     */
    public function testGreaterOrEqualThan(string $version, ?string $toCompare, bool $expected): void
    {
        $php = new Php($version);
        
        $this->assertSame(
            $expected,
            $php->isGreaterOrEqualThan($toCompare)
        );
    }
    
    public function providerTestGreaterOrEqualThan(): array
    {
        return [
            ['7', null, true],
            ['7', '7', true],
            ['8', '7', true],
            ['7.0', '7', true],
            ['7.0.1', '7', true],
            ['7.1.0', '7', true],
            ['5.6.0', '7', false],
        
            ['7', '7.1', true],
            ['8', '7.1', true],
            ['7.0', '7.1', false],
            ['7.0.1', '7.1', false],
            ['7.1.0', '7.1', true],
            ['7.1.3', '7.1', true],
            ['7.1.4', '7.1', true],
            ['5.6.0', '7.1', false],
        
            ['7', '7.1.3', true],
            ['8', '7.1.3', true],
            ['7.0', '7.1.3', false],
            ['7.0.1', '7.1.3', false],
            ['7.1.0', '7.1.3', false],
            ['7.1.3', '7.1.3', true],
            ['7.1.4', '7.1.3', true],
            ['7.2.0', '7.1.3', true],
            ['5.6.0', '7.1.3', false],
        
            ['7', '7.0.3', true],
            ['8', '7.0.3', true],
            ['7.0', '7.0.3', true],
            ['7.0.1', '7.0.3', false],
            ['7.1.0', '7.0.3', true],
            ['5.6.0', '7.0.3', false],
        
            ['7', '5.6.4', true],
            ['8', '5.6.4', true],
            ['7.0', '5.6.4', true],
            ['7.0.1', '5.6.4', true],
            ['7.5.3', '5.6.4', true],
            ['7.1.0', '5.6.4', true],
            ['5.6.0', '5.6.4', false],
            ['5.6.6', '5.6.4', true],
            ['5.6.4', '5.6.4', true],
            ['5.5.5', '5.6.4', false],
        ];
    }
    
    /**
     * @dataProvider providerTestLowerOrEqualThan
     */
    public function testLowerOrEqualThan(string $version, ?string $toCompare, bool $expected): void
    {
        $php = new Php($version);
        
        $this->assertSame(
            $expected,
            $php->isLowerOrEqualThan($toCompare)
        );
    }
    
    public function providerTestLowerOrEqualThan(): array
    {
        return [
            ['7', '7', true],
            ['8', '7', false],
            ['7.0', '7', true],
            ['7.0.1', '7', true],
            ['7.1.0', '7', true],
            ['5.6.0', '7', true],
        
      //      ['7', '7.1', true],
            ['8', '7.1', false],
            ['7.0', '7.1', true],
            ['7.0.1', '7.1', true],
            ['7.1.0', '7.1', true],
            ['7.1.3', '7.1', true],
            ['7.1.4', '7.1', true],
            ['5.6.0', '7.1', true],
        
     //       ['7', '7.1.3', true],
            ['8', '7.1.3', false],
            ['7.0', '7.1.3', true],
            ['7.0.1', '7.1.3', true],
            ['7.1.0', '7.1.3', true],
            ['7.1.3', '7.1.3', true],
            ['7.1.4', '7.1.3', false],
            ['7.2.0', '7.1.3', false],
            ['5.6.0', '7.1.3', true],
            
        //    ['7', '7.0.3', true],
            ['8', '7.0.3', false],
      //      ['7.0', '7.0.3', true],
            ['7.0.1', '7.0.3', true],
            ['7.1.0', '7.0.3', false],
            ['5.6.0', '7.0.3', true],
            
            ['7', '5.6.4', false],
            ['8', '5.6.4', false],
            ['7.0', '5.6.4', false],
            ['7.0.1', '5.6.4', false],
            ['7.5.3', '5.6.4', false],
            ['7.1.0', '5.6.4', false],
            ['5.6.0', '5.6.4', true],
            ['5.6.6', '5.6.4', false],
            ['5.6.4', '5.6.4', true],
            ['5.5.5', '5.6.4', true],
        ];
    }
}
