<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Events;

use TomPHP\TimeTracker\Common\Email;
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

    public static function fromParams(string $idString, array $params) : Event
    {
        return new self(
            DeveloperId::fromString($idString),
            $params['name'],
            Email::fromString($params['email'])
        );
    }

    public function __construct(DeveloperId $id, string $name, Email $email)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->email       = $email;
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

    public function params() : array
    {
        return [
            'name'         => $this->name,
            'email'        => (string) $this->email,
        ];
    }
}
