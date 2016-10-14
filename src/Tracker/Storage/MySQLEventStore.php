<?php

namespace TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use PDOStatement;
use TomPHP\TimeTracker\Tracker\AggregateId;
use TomPHP\TimeTracker\Tracker\Event;
use TomPHP\TimeTracker\Tracker\EventStore;

final class MySQLEventStore implements EventStore
{
    /** @var PDO */
    private $pdo;

    /** @var PDOStatement */
    private $storeStatement;

    /** @var PDOStatement */
    private $fetchStatement;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->storeStatement = $this->pdo->prepare(
            'INSERT INTO `tracker_events`'
            . ' (`name`, `aggregateId`, `aggregateName`, `data`)'
            . ' VALUES (:name, :aggregateId, :aggregateName, :data)'
        );

        $this->fetchStatement = $this->pdo->prepare(
            'SELECT * FROM'
            . ' `tracker_events`'
            . ' WHERE `aggregateId` = :aggregateId'
        );
    }

    public function store(Event $event)
    {
        $this->storeStatement->execute([
            ':name'          => get_class($event),
            ':aggregateId'   => (string) $event->aggregateId(),
            ':aggregateName' => $event->aggregateName(),
            ':data'          => serialize($event->params()),
        ]);
    }

    /** @return Event[] */
    public function fetchByAggregateId(AggregateId $id) : array
    {
        $this->fetchStatement->execute([':aggregateId' => (string) $id]);

        $results = [];
        while ($row = $this->fetchStatement->fetch(PDO::FETCH_OBJ)) {
            $eventClass = $row->name;

            $results[] = $eventClass::fromParams(
                $row->aggregateId,
                unserialize($row->data)
            );
        }

        return $results;
    }
}
