<?php

declare(strict_types = 1);

namespace Whalephant\Model;

use PHPUnit\Framework\TestCase;
use Puzzle\Assert\ArrayRelated;
use Whalephant\Model\ValueObjects\SpecificCode;
use Whalephant\Model\ValueObjects\SystemPackage;

class RecipeTest extends TestCase
{
    use ArrayRelated;
    
    /**
     * @dataProviders
     */
    public function testMergeWith(): void
    {
        $r1 = new Recipe();
        $r1->defineMinimumPhp('5.5');
        $r1->addSystemPackage(new SystemPackage('p1'));
        $r1->addIniDirective('pony');
        
        $r2 = new Recipe();
        $r2->defineMaximumPhp('7.1.2');
        $r2->addSystemPackage(new SystemPackage('p2'));
        $r2->addSystemPackage(new SystemPackage('p3'));

        $merged = $r1->mergeWith($r2);
        
        // No side effect
        self::assertEmpty($r2->iniDirectives());
        
        $this->assertPhpVersion('5.5', '7.1.2', $merged);
        $this->assertPackages(['p1', 'p2', 'p3'], $merged);
        $this->assertIniDirectives(['pony'], $merged);
        self::assertFalse($merged->automakeNeeded(), "Wrong <Need automake> flag");
        
        $r3 = new Recipe();
        $r3->needAutomake();
        $r3->defineMinimumPhp('5.4.8');
        $r3->defineMaximumPhp('7.0.9');
        
        $merged2 = $r3->mergeWith($merged);
        $this->assertPhpVersion('5.5', '7.0.9', $merged2);
        self::assertTrue($merged2->automakeNeeded(), "Wrong <Need automake> flag");
        
        $r4 = new Recipe();
        //$r4->minimumPhp('5.5.1');
        $r4->addSystemPackage(new SystemPackage('p1'));
        $r4->addSystemPackage(new SystemPackage('p1'));
        $r4->addSystemPackage(new SystemPackage('p2'));
        $r4->addSystemPackage(new SystemPackage('p4'));
        $r4->addSpecificCode(new SpecificCode('r4'));
        $r4->addIniDirective('unicorn');
        $r4->addIniDirective('pony');
        
        $merged3 = $merged2->mergeWith($r4);
        
        $this->assertPhpVersion('5.5', '7.0.9', $merged3);
        $this->assertPackages(['p1', 'p2', 'p3', 'p4'], $merged3);
        $this->assertIniDirectives(['pony', 'unicorn'], $merged3);
        $this->assertCountSpecificCode(1, $merged3);
        self::assertTrue($merged3->automakeNeeded(), "Wrong <Need automake> flag");
    }
    
    private function assertPhpVersion(string $min, string $max, Recipe $recipe): void
    {
        self::assertSame($min, $recipe->minimumPhp());
        self::assertSame($max, $recipe->maximumPhp());
    }
    
    private function assertPackages(array $expected, Recipe $recipe): void
    {
        $this->assertSameArrayExceptOrder($expected, $recipe->systemPackages()->toArrayOfStrings());
    }
    
    private function assertIniDirectives(array $expected, Recipe $recipe): void
    {
        $this->assertSameArrayExceptOrder($expected, $recipe->iniDirectives());
    }
    
    private function assertCountSpecificCode(int $expectedCount, Recipe $recipe): void
    {
        self::assertCount($expectedCount, $recipe->specificCodes());
    }
}
