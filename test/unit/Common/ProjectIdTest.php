<?php

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\ProjectId;

final class ProjectIdTest extends IdTest
{
    protected function className() : string
    {
        return ProjectId::class;
    }
}
