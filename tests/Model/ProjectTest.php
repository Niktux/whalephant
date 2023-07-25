<?php

namespace Whalephant\Model;

use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    public function testGetRecipeWithIncompatiblePhpVersion(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $project = new Project('unicorn', new Php('5.6'));
        
        $recipe = new Recipe();
        $recipe->defineMinimumPhp('7');
        
        $project->addRecipe($recipe);
        
        $project->getRecipe();
    }
}
