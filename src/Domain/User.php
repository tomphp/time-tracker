<?php

namespace TomPHP\TimeTracker\Domain;

final class User
{
    public static function create(UserId $id, string $name) : self
    {
        return new self();
    }
}
