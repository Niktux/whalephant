<?php

namespace Whalephant\Model;

use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetRecipeWithIncompatiblePhpVersion()
    {
        $project = new Project('unicorn', new Php('5.6'));
        
        $recipe = new Recipe();
        $recipe->minimumPhp('7');
        
        $project->addRecipe($recipe);
        
        $project->getRecipe();
    }
}
