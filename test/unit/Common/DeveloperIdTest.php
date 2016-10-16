<?php

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\DeveloperId;

final class DeveloperIdTest extends IdTest
{
    protected function className() : string
    {
        return DeveloperId::class;
    }
}
