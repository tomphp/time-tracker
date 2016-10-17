<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\DeveloperId;

class DeveloperIdTest extends IdTest
{
    protected function className() : string
    {
        return DeveloperId::class;
    }
}
