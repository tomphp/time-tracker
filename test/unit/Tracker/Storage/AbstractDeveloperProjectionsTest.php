<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Tracker\Storage;

use test\support\TestUsers\Fran;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

abstract class AbstractDeveloperProjectionsTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function developers() : DeveloperProjections;

    /** @test */
    public function on_withEmail_it_returns_the_developer_with_that_email()
    {
        $developerId = DeveloperId::generate();
        $developer   = new DeveloperProjection($developerId, Fran::name(), Fran::email());

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
        $developerId = DeveloperId::generate();
        $developer   = new DeveloperProjection($developerId, Fran::name(), Fran::email());

        $this->developers()->add($developer);

        assertEquals($developer, $this->developers()->withId($developerId));
    }

    /** @test */
    public function on_withId_it_throws_if_there_is_no_developer_projection_for_the_given_id()
    {
        $this->markTestIncomplete();
    }
}
