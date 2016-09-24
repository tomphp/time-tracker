<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\Date;
use TomPHP\TimeTracker\Slack\Period;

final class LogCommand implements Command
{
    /** @var string */
    private $projectName;

    /** @var Date */
    private $date;

    /** @var Period */
    private $period;

    /** @var string */
    private $description;

    public function __construct(string $projectName, Date $date, Period $period, string $description)
    {
        $this->projectName = $projectName;
        $this->date        = $date;
        $this->period      = $period;
        $this->description = $description;
    }

    public function projectName() : string
    {
        return $this->projectName;
    }

    public function date() : Date
    {
        return $this->date;
    }

    public function period() : Period
    {
        return $this->period;
    }

    public function description() : string
    {
        return $this->description;
    }
}
