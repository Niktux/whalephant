<?php

namespace Whalephant\Services;

use PHPUnit\Framework\TestCase;
use Whalephant\Services\ExtensionProviders\ArrayProvider;
use Puzzle\Configuration\Memory;
use Whalephant\Model\Project;
use Whalephant\Model\Recipe;

class ProjectBuilderTest extends TestCase
{
    private ProjectBuilder
        $builder;

    protected function setUp(): void
    {
        $this->builder = new ProjectBuilder(new ArrayProvider());
    }

    public function testBuild(): void
    {
        $config = new Memory([
            'name' => 'test',
            'php/version' => '7.1',
            'extensions' => [
                'amqp',
                'calendar',
                'gd',
                'memcached:3.0.3',
                'mysql',
                'pdo-postgresql:1.0',
                'postgresql:1.0',
                'redis',
                'xdebug:4.2.1',
                'zip',
            ],
            'ini' => [
                'error_reporting=E_ALL;',
                'date.timezone="Europe/Paris";'
            ],
        ]);

        $project = $this->builder->build($config);

        self::assertTrue($project instanceof Project);
        self::assertSame('test', $project->getName());
        self::assertSame('7.1', $project->getPhp()->version);

        $recipe = $project->getRecipe();
        self::assertTrue($recipe instanceof Recipe);
        self::assertCount(2, $recipe->getIniDirectives());
    }

    public function testParseExtensionVersion(): void
    {
        $config = new Memory([
            'name' => 'test',
            'php/version' => '7.1',
            'extensions' => [
                'xdebug:4.2.1',
            ],
        ]);

        $project = $this->builder->build($config);

        self::assertTrue($project instanceof Project);

        $recipe = $project->getRecipe();
        self::assertTrue($recipe instanceof Recipe);
        self::assertCount(1, $recipe->peclExtensions());
    }

    public function testBuildWithUnknownExtensions(): void
    {
        $this->expectException(\InvalidArgumentException::class);

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
