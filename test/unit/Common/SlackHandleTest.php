<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\SlackHandle;

final class SlackHandleTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_fromString_it_creates_with_the_at_prefix_is_ignored()
    {
        assertEquals(
            SlackHandle::fromString('tom'),
            SlackHandle::fromString('@tom')
        );
    }

    /** @test */
    public function on_value_it_returns_the_name()
    {
        assertSame('tom', SlackHandle::fromString('tom')->value());
    }

    /** @test */
    public function it_can_be_converted_to_a_string()
    {
        assertSame('@tom', (string) SlackHandle::fromString('tom'));
    }
}
