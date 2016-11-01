<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Common\Exception;

use TomPHP\TimeTracker\Common\Exception\InvalidStringFormat;

final class InvalidStringFormatTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_is_an_InvalidArgumentException()
    {
        assertInstanceOf(\InvalidArgumentException::class, new InvalidStringFormat());
    }

    /** @test */
    public function on_forClass_it_sets_the_message()
    {
        assertSame(
            '"bad string" is not a valid string for constucting an instance of ExampleClass.',
            InvalidStringFormat::forClass('bad string', 'ExampleClass')->getMessage()
        );
    }
}
