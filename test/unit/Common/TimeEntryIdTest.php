<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\TimeEntryId;

class TimeEntryIdTest extends IdTest
{
    protected function className() : string
    {
        return TimeEntryId::class;
    }
}
