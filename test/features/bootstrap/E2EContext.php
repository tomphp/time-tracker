<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use GuzzleHttp\Client;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;

class E2EContext implements Context, SnippetAcceptingContext
{
    use CommonTransforms;

    const SLACK_ENDPOINT = '/slack/slash-command-endpoint';

    /** Client */
    private $client;

    /** @var array */
    private $developers = [];

    /** @var array */
    private $projects;

    /** @var string */
    private $today = '2016-09-19';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:8080/',
        ]);
    }

    /**
     * @Given :name is a developer with Slack handle @:slackHandle
     */
    public function createDeveloper(string $name, SlackHandle $slackHandle)
    {
        $id                      = DeveloperId::generate();
        $this->developers[$name] = ['id' => (string) $id, 'slack_handle' => $slackHandle];
        Developer::create(DeveloperId::generate(), $name, $slackHandle);
    }

    /**
     * @Given there is a project named :name
     */
    public function createProject(string $name)
    {
        $projectId             = ProjectId::generate();
        $this->projects[$name] = ['id' => (string) $projectId];
        Project::create($projectId, $name);
    }

    /**
     * @When :developer issues the command :command
     */
    public function tomIssuesTheCommand(string $name, string $command)
    {
        $this->client->post(
            self::SLACK_ENDPOINT,
            [
                'form_params' => [
                    'token'        => 'gIkuvaNzQIHg97ATvDxqgjtO',
                    'team_id'      => 'T0001',
                    'team_domain'  => 'example',
                    'channel_id'   => 'C2147483705',
                    'channel_name' => 'test',
                    'user_id'      => 'U2147483697',
                    'user_name'    => $this->developers[$name]['slack_handle']->value(),
                    'command'      => "/tt $command",
                    'text'         => '94070',
                    'response_url' => 'https://hooks.slack.com/commands/1234/5678',
                ],
            ]
        );
    }

    /**
     * @Then :period hours should have been logged today by :developerName against :projectName for :description
     */
    public function assertTimeLoggedAgainstProject(
        $period,
        string $developerName,
        string $projectName,
        string $description
    ) {
        $projectId = $this->projects[$projectName]['id'];

        $response = $this->client->get("/api/v1/projects/$projectId/time-entries");

        $timeEntries = json_decode((string)$response->getBody());

        $expectedEntry = (object) [
            'projectId'   => $this->projects[$projectName]['id'],
            'developerId' => $this->developers[$developerName]['id'],
            'date'        => $this->today,
            'period'      => $period,
            'description' => $description,
        ];

        assertEquals($expectedEntry, $timeEntries[0]);
    }

    /**
     * @Then message saying :message should have been sent to Slack
     */
    public function messageSayingShouldHaveBeenSentToSlack(string $message)
    {
        throw new PendingException();
        // Mountebank?
    }
}
