<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use Prophecy\Argument;
use test\support\TestUsers\IngredientInventory;
use test\support\TestUsers\Mike;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\TimeTracker;
use TomPHP\TimeTracker\Tracker;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

final class TimeTrackerTest extends \PHPUnit_Framework_TestCase
{
    /** @var TimeTracker */
    private $subject;

    /** @var DeveloperProjections */
    private $developers;

    /** @var ProjectProjections */
    private $projects;

    protected function setUp()
    {
        $this->developers = $this->prophesize(DeveloperProjections::class);
        $this->projects   = $this->prophesize(ProjectProjections::class);

        $this->developers
            ->withEmail(Argument::any())
            ->willReturn(new DeveloperProjection(
                Tracker\DeveloperId::fromString((string) Mike::id()),
                Mike::name(),
                Mike::email()
            ));
        $this->developers
            ->withId(Argument::any())
            ->willReturn(new DeveloperProjection(
                Tracker\DeveloperId::fromString((string) Mike::id()),
                Mike::name(),
                Mike::email()
            ));

        $this->projects
            ->withName(Argument::any())
            ->willReturn(new ProjectProjection(
                ProjectId::fromString((string) IngredientInventory::id()),
                IngredientInventory::name(),
                Period::fromString('1')
            ));

        $this->subject = new TimeTracker(
            $this->developers->reveal(),
            $this->projects->reveal()
        );
    }

    /** @test */
    public function on_fetchDeveloperByEmail_it_fetches_the_DeveloperProjection_by_email()
    {
        $this->subject->fetchDeveloperByEmail(Mike::email());

        $this->developers->withEmail(Mike::email())->shouldHaveBeenCalled();
    }

    /** @test */
    public function on_fetchDeveloperByEmail_it_returns_the_developer()
    {
        assertEquals(
            new Developer(Mike::id(), Mike::name()),
            $this->subject->fetchDeveloperByEmail(Mike::email())
        );
    }

    /** @test */
    public function on_fetchDeveloperById_it_fetches_the_DeveloperProjection_by_id()
    {
        $this->subject->fetchDeveloperById(Mike::id());

        $this->developers->withId(\TomPHP\TimeTracker\Tracker\DeveloperId::fromString((string) Mike::id()))
            ->shouldHaveBeenCalled();
    }

    /** @test */
    public function on_fetchDeveloperById_it_returns_the_developer()
    {
        assertEquals(
            new Developer(Mike::id(), Mike::name()),
            $this->subject->fetchDeveloperById(Mike::id())
        );
    }

    /** @test */
    public function on_fetchProjectByName_it_fetches_the_project_by_name()
    {
        $this->subject->fetchProjectByName('Time Tracker');

        $this->projects->withName('Time Tracker')->shouldHaveBeenCalled();
    }

    /** @test */
    public function on_fetchProjectByName_it_returns_the_project()
    {
        assertEquals(
            IngredientInventory::asSlackProject(),
            $this->subject->fetchProjectByName('Time Tracker')
        );
    }

    /** @test */
    public function on_logTimeEntry_log_the_entry()
    {
        $this->markTestIncomplete('Use a command bus instance');
    }
}
