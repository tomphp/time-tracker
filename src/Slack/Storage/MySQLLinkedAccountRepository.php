<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Storage;

use Aura\SqlQuery\AbstractQuery;
use Aura\SqlQuery\QueryFactory;
use PDO;
use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\SlackUserId;

final class MySQLLinkedAccountRepository implements LinkedAccounts
{
    private const TABLE_NAME = 'slack_linked_accounts';

    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(LinkedAccount $account)
    {
        $this->insert([
            'developerId' => (string) $account->developerId(),
            'slackUserId' => (string) $account->slackUserId(),
        ]);
    }

    public function hasSlackUser(SlackUserId $slackUserId) : bool
    {
        return (bool) $this->selectOne('slackUserId', (string) $slackUserId);
    }

    public function hasDeveloper(DeveloperId $developerId) : bool
    {
        return (bool) $this->selectOne('developerId', (string) $developerId);
    }

    public function withSlackUserId(SlackUserId $slackUserId) : LinkedAccount
    {
        $row = $this->selectOne('slackUserId', (string) $slackUserId);

        return new LinkedAccount(DeveloperId::fromString($row->developerId), $slackUserId);
    }

    private function selectOne(string $field, $value)
    {
        $select = $this->queryFactory()->newSelect();

        $select
            ->cols(['*'])
            ->from(self::TABLE_NAME)
            ->where("$field = ?", (string) $value);

        $statement = $this->executeQuery($select);

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    private function insert(array $cols)
    {
        $insert = $this->queryFactory()->newInsert();
        $insert
            ->into(self::TABLE_NAME)
            ->cols($cols);

        $this->executeQuery($insert);
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
