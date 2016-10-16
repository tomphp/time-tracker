<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntry;

/** @final */
class TimeTracker
{
    /** @var DeveloperProjections */
    private $developers;

    /** @var ProjectProjections */
    private $projects;

    public function __construct(
        DeveloperProjections $developers,
        ProjectProjections $projects
    ) {
        $this->developers = $developers;
        $this->projects   = $projects;
    }

    public function fetchDeveloperByEmail(Email $email) : Developer
    {
        $developer = $this->developers->withEmail($email);

        return new Developer(
            Common\DeveloperId::fromString((string) $developer->id()),
            $developer->name(),
            $developer->slackHandle()
        );
    }

    public function fetchDeveloperBySlackHandle(SlackHandle $slackHandle) : Developer
    {
        $developer = $this->developers->withSlackHandle($slackHandle);

        return new Developer(
            Common\DeveloperId::fromString((string) $developer->id()),
            $developer->name(),
            $developer->slackHandle()
        );
    }

    public function fetchProjectByName(string $name) : Project
    {
        $project = $this->projects->withName($name);

        return new Project(
            Common\ProjectId::fromString((string) $project->id()),
            $project->name()
        );
    }

    /** @return void */
    public function logTimeEntry(
        Developer $developer,
        Project $project,
        Date $date,
        Period $period,
        string $description
    ) {
        TimeEntry::log(
            Tracker\TimeEntryId::generate(),
            Tracker\DeveloperId::fromString((string) $developer->id()),
            Tracker\ProjectId::fromString((string) $project->id()),
            $date,
            $period,
            $description
        );
    }
}
