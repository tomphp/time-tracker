<?php

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Slack\Command;

final class LinkCommand implements Command
{
    /** @var Email */
    private $email;

    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function email() : Email
    {
        return $this->email;
    }
}
