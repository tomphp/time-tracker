<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker;

use TomPHP\TimeTracker\Common\DeveloperId as Id;
use TomPHP\TimeTracker\Common\Email;

final class DeveloperProjection
{
    /** @var Id */
    private $id;

    /** @var string */
    private $name;

    /** @var Email */
    private $email;

    public function __construct(Id $id, string $name, Email $email)
    {
        $this->id    = $id;
        $this->name  = $name;
        $this->email = $email;
    }

    public function id() : Id
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
