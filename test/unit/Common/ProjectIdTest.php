<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Common;

use TomPHP\TimeTracker\Common\ProjectId;

class ProjectIdTest extends IdTest
{
    protected function className() : string
    {
        return ProjectId::class;
    }
}
