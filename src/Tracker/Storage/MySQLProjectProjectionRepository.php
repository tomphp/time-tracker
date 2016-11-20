<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;

final class MySQLProjectProjectionRepository implements ProjectProjections
{
    use MySQLTools;

    const TABLE_NAME = 'project_projections';

    public function all() : array
    {
        return $this->selectAll();
    }

    public function add(ProjectProjection $project)
    {
        $this->insert($project);
    }

    public function withId(ProjectId $id) : ProjectProjection
    {
        return $this->selectOne('id', (string) $id);
    }

    public function hasWithName(string $name) : bool
    {
        $statement = $this->pdo->prepare('SELECT * FROM `project_projections` WHERE `name` = :name');

        $statement->execute([':name' => $name]);

        return (bool) $statement->fetch(\PDO::FETCH_OBJ);
    }

    public function withName(string $name) : ProjectProjection
    {
        return $this->selectOne('name', $name);
    }

    public function updateTotalTimeFor(ProjectId $id, Period $totalTime)
    {
        $statement = $this->pdo->prepare('UPDATE `project_projections` SET totalTime = :totalTime WHERE `id` = :id');

        $statement->execute([
            ':id'        => (string) $id,
            ':totalTime' => (string) $totalTime,
        ]);
    }

    /** @return ProjectProjection */
    protected function create(\stdClass $fields)
    {
        return new ProjectProjection(
            ProjectId::fromString($fields->id),
            $fields->name,
            Period::fromString($fields->totalTime)
        );
    }

    /**
     * @param ProjectProjection $project
     */
    protected function extract($project) : array
    {
        return [
            'id'        => (string) $project->id(),
            'name'      => $project->name(),
            'totalTime' => (string) $project->totalTime(),
        ];
    }
}
