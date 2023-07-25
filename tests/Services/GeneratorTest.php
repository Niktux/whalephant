<?php

namespace Whalephant\Services;

use PHPUnit\Framework\TestCase;
use Whalephant\Container;
use Puzzle\Configuration\Memory;
use Gaufrette\Filesystem;
use Gaufrette\Adapter\InMemory;

class GeneratorTest extends TestCase
{
    private Generator
        $generator;
    
    protected function setUp(): void
    {
        $container = new Container(new Memory([
            'app/var.path' => 'var',
        ]), __DIR__ . '/../..');
        
        $this->generator = new Generator($container['project.builder'], $container['twig']);
    }
    
    public function testGenerate(): void
    {
        $fs = new Filesystem(new InMemory());
        
        $fs->write(Container::WHALEPHANT_FILENAME, <<<YAML
name: test
php:
    version: 5.6
extensions:
    - meminfo:5
YAML
);
        $this->generator->generate($fs);
        
        $this->assertTrue($fs->has('Dockerfile'));
        $this->assertTrue($fs->has('php.ini'));
    }
    
    public function testGenerateMissingWhalephantFile(): void
    {
        $this->expectException(\Exception::class);

        $this->generator->generate(new Filesystem(new InMemory()));
    }
}
