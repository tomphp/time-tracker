<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use test\support\TestUsers\IngredientInventory;

final class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_values()
    {
        $project = IngredientInventory::asSlackProject();

        assertEquals(IngredientInventory::id(), $project->id());
        assertEquals(IngredientInventory::name(), $project->name());
    }
}
