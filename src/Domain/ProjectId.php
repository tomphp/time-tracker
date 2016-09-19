<?php

namespace TomPHP\TimeTracker\Domain;

final class ProjectId
{
    public static function generate() : self
    {
        return new self();
    }
}
