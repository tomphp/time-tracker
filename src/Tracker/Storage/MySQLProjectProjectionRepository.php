<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

final class MySQLProjectProjectionRepository implements ProjectProjections
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all() : array
    {
        $statement = $this->pdo->query('SELECT * FROM `project_projections`');

        $results = [];

        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $results[] = new ProjectProjection(
                ProjectId::fromString($row->id),
                $row->name,
                Period::fromString($row->totalTime)
            );
        }

        return $results;
    }

    public function add(ProjectProjection $project)
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO `project_projections`'
            . ' (`id`, `name`, `totalTime`)'
            . ' VALUES (:id, :name, :totalTime)'
        );

        $statement->execute([
            ':id'        => (string) $project->id(),
            ':name'      => $project->name(),
            ':totalTime' => (string) $project->totalTime(),
        ]);
    }

    public function withId(ProjectId $id) : ProjectProjection
    {
        $statement = $this->pdo->prepare('SELECT * FROM `project_projections` WHERE `id` = :id');

        $statement->execute([':id' => (string) $id]);

        $row = $statement->fetch(PDO::FETCH_OBJ);

        return new ProjectProjection(
            ProjectId::fromString($row->id),
            $row->name,
            Period::fromString($row->totalTime)
        );
    }

    public function hasWithName(string $name) : bool
    {
        $statement = $this->pdo->prepare('SELECT * FROM `project_projections` WHERE `name` = :name');

        $statement->execute([':name' => $name]);

        return (bool) $statement->fetch(PDO::FETCH_OBJ);
    }

    public function withName(string $name) : ProjectProjection
    {
        $statement = $this->pdo->prepare('SELECT * FROM `project_projections` WHERE `name` = :name');

        $statement->execute([':name' => $name]);

        $row = $statement->fetch(PDO::FETCH_OBJ);

        return new ProjectProjection(
            ProjectId::fromString($row->id),
            $row->name,
            Period::fromString($row->totalTime)
        );
    }

    public function updateTotalTimeFor(ProjectId $id, Period $totalTime)
    {
        $statement = $this->pdo->prepare('UPDATE `project_projections` SET totalTime = :totalTime WHERE `id` = :id');

        $statement->execute([
            ':id'        => (string) $id,
            ':totalTime' => (string) $totalTime,
        ]);
    }
}
