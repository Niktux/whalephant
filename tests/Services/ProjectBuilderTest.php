<?php

namespace Whalephant\Services;

use PHPUnit\Framework\TestCase;
use Whalephant\Services\ExtensionProviders\ArrayProvider;
use Puzzle\Configuration\Memory;
use Whalephant\Model\Project;
use Whalephant\Model\Recipe;

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
                'gd',
                'xdebug:4.2.1',
                'memcached:3.0.3',
                'mysql',
                'postgresql:1.0',
                'redis',
                'zip',
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
        
        $recipe = $project->getRecipe();
        $this->assertTrue($recipe instanceof Recipe);
        $this->assertCount(2, $recipe->getIniDirectives());
    }
    
    public function testParseExtensionVersion()
    {
        $config = new Memory([
            'name' => 'test',
            'php/version' => '7.1',
            'extensions' => [
                'xdebug:4.2.1',
            ],
        ]);
        
        $project = $this->builder->build($config);
        
        $this->assertTrue($project instanceof Project);
        
        $recipe = $project->getRecipe();
        $this->assertTrue($recipe instanceof Recipe);
        $this->assertCount(1, $recipe->getPeclPackagesToInstall());
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
