<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use test\unit\TomPHP\TimeTracker\Common\IdTest;
use TomPHP\TimeTracker\Slack\SlackUserId;

final class SlackUserIdTest extends IdTest
{
    protected function className() : string
    {
        return SlackUserId::class;
    }
}
