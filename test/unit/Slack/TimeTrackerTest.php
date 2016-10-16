<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use Prophecy\Argument;
use test\support\TestUsers\Mike;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Developer;
use TomPHP\TimeTracker\Slack\Project;
use TomPHP\TimeTracker\Slack\TimeTracker;
use TomPHP\TimeTracker\Tracker;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

final class TimeTrackerTest extends \PHPUnit_Framework_TestCase
{
    const PROJECT_ID   = 'project-id';
    const PROJECT_NAME = 'Time Tracker';

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
            ->withSlackHandle(Argument::any())
            ->willReturn(new DeveloperProjection(
                Tracker\DeveloperId::fromString((string) Mike::id()),
                Mike::name(),
                Mike::email(),
                Mike::slackHandle()
            ));

        $this->projects
            ->withName(Argument::any())
            ->willReturn(new ProjectProjection(
                ProjectId::fromString(self::PROJECT_ID),
                self::PROJECT_NAME,
                Period::fromString('1')
            ));

        $this->subject = new TimeTracker(
            $this->developers->reveal(),
            $this->projects->reveal()
        );
    }

    /** @test */
    public function on_fetchDeveloperBySlackHandle_it_fetches_the_DeveloperProject_by_slack_handle()
    {
        $this->subject->fetchDeveloperBySlackHandle(SlackHandle::fromString('@tom'));

        $this->developers->withSlackHandle(SlackHandle::fromString('@tom'))->shouldHaveBeenCalled();
    }

    /** @test */
    public function on_fetchDeveloperBySlackHandle_it_returns_the_developer()
    {
        assertEquals(
            new Developer(Mike::id(), Mike::name(), Mike::slackHandle()),
            $this->subject->fetchDeveloperBySlackHandle(SlackHandle::fromString('@tom'))
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
            new Project(self::PROJECT_ID, self::PROJECT_NAME),
            $this->subject->fetchProjectByName('Time Tracker')
        );
    }

    /** @test */
    public function on_logTimeEntry_log_the_entry()
    {
        $this->markTestIncomplete('Use a command bus instance');
    }
}
