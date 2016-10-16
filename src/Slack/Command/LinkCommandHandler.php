<?php

namespace TomPHP\TimeTracker\Slack\Command;

use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\Command;
use TomPHP\TimeTracker\Slack\CommandHandler;
use TomPHP\TimeTracker\Slack\TimeTracker;

final class LinkCommandHandler implements CommandHandler
{
    const SUCCESS_MESSAGE = 'Hi %s, your account has been successfully linked.';

    /** @var TimeTracker */
    private $timeTracker;

    public function __construct(TimeTracker $timeTracker)
    {
        $this->timeTracker = $timeTracker;
    }

    public function handle(SlackHandle $slackHandle, Command $command) : array
    {
        $developer = $this->timeTracker->fetchDeveloperByEmail($command->email());

        return [
            'response_type' => 'ephemeral',
            'text'          => sprintf(self::SUCCESS_MESSAGE, $developer->name()),
        ];
    }
}
