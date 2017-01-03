<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use Aura\SqlQuery\AbstractQuery;
use Aura\SqlQuery\QueryFactory;

trait MySQLTools
{
    /** @var \PDO */
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    abstract protected function create(\stdClass $array);

    abstract protected function extract($object) : array;

    protected function selectAll() : array
    {
        $select = $this->queryFactory()->newSelect();

        $select
            ->cols(['*'])
            ->from(self::TABLE_NAME);

        return $this->multiSelect($select);
    }

    protected function selectWhere(string $field, $value) : array
    {
        $select = $this->queryFactory()->newSelect();

        $select
            ->cols(['*'])
            ->from(self::TABLE_NAME)
            ->where("$field = ?", (string) $value);

        return $this->multiSelect($select);
    }

    protected function selectOne(string $field, $value)
    {
        $select = $this->queryFactory()->newSelect();

        $select
            ->cols(['*'])
            ->from(self::TABLE_NAME)
            ->where("$field = ?", (string) $value);

        $statement = $this->executeQuery($select);

        $row = $statement->fetch(\PDO::FETCH_OBJ);

        return $this->create($row);
    }

    protected function insert($object)
    {
        $insert = $this->queryFactory()->newInsert();
        $insert
            ->into(self::TABLE_NAME)
            ->cols($this->extract($object));

        $this->executeQuery($insert);
    }

    private function multiSelect($select) : array
    {
        $statement = $this->executeQuery($select);

        $result = [];
        while ($row = $statement->fetch(\PDO::FETCH_OBJ)) {
            $result[] = $this->create($row);
        }

        return $result;
    }

    private function executeQuery(AbstractQuery $query) : \PDOStatement
    {
        $statement = $this->pdo->prepare($query->getStatement());
        $statement->execute($query->getBindValues());

        return $statement;
    }

    private function queryFactory() : QueryFactory
    {
        return new QueryFactory('mysql');
    }
}
