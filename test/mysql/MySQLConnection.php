<?php

namespace test\mysql\TomPHP\TimeTracker;

use PDO;

trait MySQLConnection
{
    /** @var PDO */
    private $pdo;

    protected function pdo() : PDO
    {
        if (!$this->pdo) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s',
                getenv('MYSQL_HOSTNAME'),
                getenv('MYSQL_DBNAME')
            );

            $this->pdo = new PDO($dsn, getenv('MYSQL_USERNAME'), getenv('MYSQL_PASSWORD'));
        }

        return $this->pdo;
    }

    protected function clearTable(string $name)
    {
        $this->pdo()->exec("TRUNCATE `$name`");
    }
}
