<?php

namespace TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

final class MySQLTimeEntryProjectionRepository implements TimeEntryProjections
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function forProject(ProjectId $projectId) : array
    {
        $statement = $this->pdo->prepare('SELECT * FROM `time_entry_projections` WHERE `projectId` = :projectId');

        $statement->execute([':projectId' => (string) $projectId]);

        $results = [];

        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $results[] = new TimeEntryProjection(
                TimeEntryId::fromString($row->id),
                DeveloperId::fromString($row->developerId),
                ProjectId::fromString($row->projectId),
                Date::fromString($row->date),
                Period::fromString($row->period),
                $row->description
            );
        }

        return $results;
    }

    public function add(TimeEntryProjection $projection)
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO `time_entry_projections`'
            . ' (`id`, `projectId`, `developerId`, `date`, `period`, `description`)'
            . ' VALUES (:id, :projectId, :developerId, :date, :period, :description)'
        );

        $statement->execute([
            ':id'          => (string) $projection->id(),
            ':projectId'   => (string) $projection->projectId(),
            ':developerId' => (string) $projection->developerId(),
            ':date'        => (string) $projection->date(),
            ':period'      => (string) $projection->period(),
            ':description' => $projection->description(),
        ]);
    }
}
