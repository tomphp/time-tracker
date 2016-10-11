<?php

namespace TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

final class MySQLDeveloperProjectionRepository implements DeveloperProjections
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(DeveloperProjection $developer)
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO `developer_projections`'
            . ' (`id`, `name`, `slackHandle`)'
            . ' VALUES (:id, :name, :slackHandle)'
        );

        $statement->execute([
            ':id'          => $developer->id(),
            ':name'        => $developer->name(),
            ':slackHandle' => (string) $developer->slackHandle(),
        ]);
    }

    public function withId(DeveloperId $id) : DeveloperProjection
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM'
            . ' `developer_projections`'
            . ' WHERE `id` = :id'
        );

        $statement->execute([':id' => (string) $id]);

        // TODO: check row count

        $row = $statement->fetch(PDO::FETCH_OBJ);

        return new DeveloperProjection(
            DeveloperId::fromString($row->id),
            $row->name,
            SlackHandle::fromString($row->slackHandle)
        );
    }

    public function withSlackHandle(SlackHandle $handle) : DeveloperProjection
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM'
            . ' `developer_projections`'
            . ' WHERE `slackHandle` = :slackHandle'
        );

        $statement->execute([':slackHandle' => (string) $handle]);

        // TODO: check row count

        $row = $statement->fetch(PDO::FETCH_OBJ);

        return new DeveloperProjection(
            DeveloperId::fromString($row->id),
            $row->name,
            SlackHandle::fromString($row->slackHandle)
        );
    }
}
