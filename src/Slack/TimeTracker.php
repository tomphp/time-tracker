<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

/** @final */
class TimeTracker
{
    public function fetchDeveloperBySlackHandle(string $slackHandle) : Developer
    {
        return new Developer();
    }

    public function fetchProjectByName(string $name) : Project
    {
    }

    /** @return void */
    public function logTimeEntry(
        Developer $developer,
        Project $project,
        Date $date,
        Period $period,
        string $description
    ) {
    }
}
