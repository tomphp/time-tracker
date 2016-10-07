<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Slack;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Developer;

final class DeveloperTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_exposes_its_values()
    {
        $developer = new Developer('id', 'name', SlackHandle::fromString('slack'));

        assertSame('id', $developer->id());
        assertSame('name', $developer->name());
        assertEquals(SlackHandle::fromString('slack'), $developer->slackHandle());
    }
}
