<?php

namespace Whalephant\Services;

use PHPUnit\Framework\TestCase;
use Whalephant\Services\ExtensionProviders\ArrayProvider;
use Puzzle\Configuration\Memory;
use Whalephant\Model\Project;

class ProjectBuilderTest extends TestCase
{
    private
        $builder;
    
    protected function setUp()
    {
        $this->builder = new ProjectBuilder(new ArrayProvider());
    }
    
    public function testBuild()
    {
        $config = new Memory([
            'name' => 'test',
            'php/version' => '7.1',
            'extensions' => [
                'amqp',
                'xdebug',
                'zlib',
            ],
            'ini' => [
                'error_reporting=E_ALL;',
                'date.timezone="Europe/Paris";'
            ],
        ]);
        
        $project = $this->builder->build($config);
        
        $this->assertTrue($project instanceof Project);
        $this->assertSame('test', $project->getName());
        $this->assertSame('7.1', $project->getPhp()->version);
        $this->assertCount(2, $project->getRecipe()->getIniDirectives());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBuildWithUnknownExtensions()
    {
        $config = new Memory([
            'name' => 'fail',
            'php/version' => 7,
            'extensions' => [
                'amqp',
                'unicorn',
            ]
        ]);
        
        $this->builder->build($config);
    }
}
