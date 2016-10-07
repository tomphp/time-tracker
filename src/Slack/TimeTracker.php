<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;
use TomPHP\TimeTracker\Tracker\ProjectId;
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

    public function fetchDeveloperBySlackHandle(SlackHandle $slackHandle) : Developer
    {
        $developer = $this->developers->withSlackHandle($slackHandle);

        return new Developer(
            (string) $developer->id(),
            $developer->name(),
            $developer->slackHandle()
        );
    }

    public function fetchProjectByName(string $name) : Project
    {
        $project = $this->projects->withName($name);

        return new Project(
            (string) $project->id(),
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
            DeveloperId::fromString($developer->id()),
            ProjectId::fromString($project->id()),
            $date,
            $period,
            $description
        );
    }
}
