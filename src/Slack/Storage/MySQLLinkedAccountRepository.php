<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Slack\Storage;

use PDO;
use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Slack\LinkedAccount;
use TomPHP\TimeTracker\Slack\LinkedAccounts;
use TomPHP\TimeTracker\Slack\SlackUserId;

final class MySQLLinkedAccountRepository implements LinkedAccounts
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(LinkedAccount $account)
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO `slack_linked_accounts`'
            . ' (`developerId`, `slackUserId`)'
            . ' VALUES (:developerId, :slackUserId)'
        );

        $statement->execute([
            ':developerId' => (string) $account->developerId(),
            ':slackUserId' => (string) $account->slackUserId(),
        ]);
    }

    public function hasSlackUser(SlackUserId $slackUserId) : bool
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM'
            . ' `slack_linked_accounts`'
            . ' WHERE `slackUserId` = :slackUserId'
        );

        $statement->execute([':slackUserId' => (string) $slackUserId]);

        return (bool) $statement->fetch(PDO::FETCH_OBJ);
    }

    public function hasDeveloper(string $developerId) : bool
    {
    }

    public function withSlackUserId(SlackUserId $slackUserId) : LinkedAccount
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM'
            . ' `slack_linked_accounts`'
            . ' WHERE `slackUserId` = :slackUserId'
        );

        $statement->execute([':slackUserId' => (string) $slackUserId]);

        // TODO: check row count

        $row = $statement->fetch(PDO::FETCH_OBJ);

        return new LinkedAccount(
            DeveloperId::fromString($row->developerId),
            $slackUserId
        );
    }
}
