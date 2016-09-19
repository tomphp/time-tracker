<?php

namespace TomPHP\TimeTracker\Domain;

final class Project
{
    public static function create(ProjectId $id, string $name) : self
    {
        return new self();
    }
}
