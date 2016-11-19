<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Storage;

use test\support\TestUsers\Fran;
use test\support\TestUsers\Mike;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

abstract class AbstractDeveloperProjectionsTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function developers() : DeveloperProjections;

    /** @test */
    public function on_all_it_returns_all_added_developer_projections()
    {
        $mike = new DeveloperProjection(Mike::id(), Mike::name(), Mike::email());
        $fran = new DeveloperProjection(Fran::id(), Fran::name(), Fran::email());

        $this->developers()->add($mike);
        $this->developers()->add($fran);

        assertEquals(
            $this->sortDevelopers([$mike, $fran]),
            $this->sortDevelopers($this->developers()->all())
        );
    }

    /** @test */
    public function on_withEmail_it_returns_the_developer_with_that_email()
    {
        $developer   = new DeveloperProjection(Fran::id(), Fran::name(), Fran::email());

        $this->developers()->add($developer);

        assertEquals($developer, $this->developers()->withEmail(Fran::email()));
    }

    /** @test */
    public function on_withEmail_it_throws_if_there_is_no_developer_projection_with_the_given_email()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function on_withId_it_returns_the_developer_projection_with_that_id()
    {
        $developer   = new DeveloperProjection(Fran::id(), Fran::name(), Fran::email());

        $this->developers()->add($developer);

        assertEquals($developer, $this->developers()->withId(Fran::id()));
    }

    /** @test */
    public function on_withId_it_throws_if_there_is_no_developer_projection_for_the_given_id()
    {
        $this->markTestIncomplete();
    }

    private function sortDevelopers(array $projects) : array
    {
        usort(
            $projects,
            function (DeveloperProjection $a, DeveloperProjection $b) {
                return (string) $a->id() <=> (string) $b->id();
            }
        );

        return $projects;
    }
}
