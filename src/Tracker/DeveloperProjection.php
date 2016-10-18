<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\Email;

final class DeveloperProjection
{
    /** @var DeveloperId */
    private $id;

    /** @var string */
    private $name;

    /** @var Email */
    private $email;

    public function __construct(DeveloperId $id, string $name, Email $email)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->email       = $email;
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
}
