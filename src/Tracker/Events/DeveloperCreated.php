<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Event;

final class DeveloperCreated extends Event
{
    /** @var DeveloperId */
    private $id;

    /** @var string */
    private $name;

    /** @var Email */
    private $email;

    /** @var SlackHandle */
    private $slackHandle;

    public static function fromParams(string $idString, array $params) : Event
    {
        return new self(
            DeveloperId::fromString($idString),
            $params['name'],
            Email::fromString($params['email']),
            SlackHandle::fromString($params['slack_handle'])
        );
    }

    public function __construct(DeveloperId $id, string $name, Email $email, SlackHandle $slackHandle)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->email       = $email;
        $this->slackHandle = $slackHandle;
    }

    public function aggregateId() : AggregateId
    {
        return $this->id;
    }

    public function aggregateName() : string
    {
        return Developer::class;
    }

    public function id() : DeveloperId
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function email() : Email
    {
        return $this->email;
    }

    public function slackHandle() : SlackHandle
    {
        return $this->slackHandle;
    }

    public function params() : array
    {
        return [
            'name'         => $this->name,
            'email'        => (string) $this->email,
            'slack_handle' => (string) $this->slackHandle,
        ];
    }
}
