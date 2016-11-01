<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Common\Exception;

use TomPHP\ExceptionConstructorTools;

final class InvalidStringFormat extends \LogicException
{
    use ExceptionConstructorTools;

    public static function forClass(string $string, string $class) : self
    {
        return self::create(
            '"%s" is not a valid string for constucting an instance of %s.',
            [$string, $class]
        );
    }
}
