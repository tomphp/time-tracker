<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\DeveloperId;

final class Developer
{
    /** @var DeveloperId */
    private $id;

    /** @var string */
    private $name;

    public function __construct(DeveloperId $id, string $name)
    {
        $this->id          = $id;
        $this->name        = $name;
    }

    public function id() : DeveloperId
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }
}
