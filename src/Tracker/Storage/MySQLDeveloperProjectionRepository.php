<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use PDO;
use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Common\Email;
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
            . ' (`id`, `name`, `email`)'
            . ' VALUES (:id, :name, :email)'
        );

        $statement->execute([
            ':id'          => $developer->id(),
            ':name'        => $developer->name(),
            ':email'       => $developer->email(),
        ]);
    }

    public function all() : array
    {
        $statement = $this->pdo->query('SELECT * FROM `developer_projections`');

        $results = [];

        while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
            $results[] = new DeveloperProjection(
                DeveloperId::fromString($row->id),
                $row->name,
                Email::fromString($row->email)
            );
        }

        return $results;
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
            Email::fromString($row->email)
        );
    }

    public function withEmail(Email $email) : DeveloperProjection
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM'
            . ' `developer_projections`'
            . ' WHERE `email` = :email'
        );

        $statement->execute([':email' => (string) $email]);

        // TODO: check row count

        $row = $statement->fetch(PDO::FETCH_OBJ);

        return new DeveloperProjection(
            DeveloperId::fromString($row->id),
            $row->name,
            Email::fromString($row->email)
        );
    }
}
