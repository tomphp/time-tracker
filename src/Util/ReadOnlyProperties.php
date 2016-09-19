<?php

namespace TomPHP\TimeTracker\Util;

trait ReadOnlyProperties
{
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __isset($name): bool
    {
        return isset($this->$name);
    }
}
