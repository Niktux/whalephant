<?php

namespace Whalephant\Services;

use PHPUnit\Framework\TestCase;
use Whalephant\Application;
use Puzzle\Configuration\Memory;
use Gaufrette\Filesystem;
use Gaufrette\Adapter\InMemory;

class GeneratorTest extends TestCase
{
    private
        $generator;
    
    protected function setUp()
    {
        $container = new Application(new Memory([
            'app/var.path' => 'var',
        ]), __DIR__ . '/../..');
        
        $this->generator = new Generator($container['project.builder'], $container['twig']);
    }
    
    public function testGenerate()
    {
        $fs = new Filesystem(new InMemory());
        
        $fs->write(Application::WHALEPHANT_FILENAME, <<<YAML
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
    
    /**
     * @expectedException \Exception
     */
    public function testGenerateMissingWhalephantFile()
    {
        $this->generator->generate(new Filesystem(new InMemory()));
    }
}
