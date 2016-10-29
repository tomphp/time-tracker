<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Art4\JsonApiClient\Document;
use Art4\JsonApiClient\Utils\Manager;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Slim\Container;
use TomPHP\TimeTracker\Bootstrap;
use TomPHP\TimeTracker\Common\Date;
use TomPHP\TimeTracker\Common\Period;

class E2EContext implements Context, SnippetAcceptingContext
{
    use CommonTransforms;

    const SLACK_ENDPOINT = '/slack/slash-command-endpoint';
    const REST_ENDPOINT  = '/api/v1';

    /** Client */
    private $client;

    /** @var array */
    private $developers = [];

    /** @var array */
    private $projects;

    /** @var Manager */
    private $jsonApiManager;

    /** @var SlackUserId[] */
    private $slackUsers = [];

    /** @var array|\stdClass */
    private $result;

    public function __construct()
    {
        $this->jsonApiManager = new Manager();

        $this->client = new Client([
            'base_uri'        => getenv('SITE_URL'),
            'allow_redirects' => false,
        ]);

        $services = new Container();

        Bootstrap::run($services);

        $services->get('database')->exec('TRUNCATE `slack_linked_accounts`');
        $services->get('database')->exec('TRUNCATE `developer_projections`');
        $services->get('database')->exec('TRUNCATE `project_projections`');
        $services->get('database')->exec('TRUNCATE `time_entry_projections`');
    }

    /**
     * @Given :name has a developer account with email :email
     */
    public function createDeveloperWithEmail(string $name, string $email)
    {
        $response = $this->client->post(
            '/api/v1/developers',
            [
                'json' => [
                    'name'         => $name,
                    'email'        => $email,
                    'slack-handle' => "@$name",
                ],
            ]
        );

        $id = $this->assertCreatedResponseAndGetId($response, '!^.*/api/v1/developers/(.*?)$!');

        $this->developers[$name] = ['id' => $id];
    }

    /**
     * @Given :developerName has a Slack account
     */
    public function createSlackUser(string $developerName)
    {
        $count = count($this->slackUsers);
        $id    = sprintf('U99999999%02f', $count);

        $this->slackUsers[$developerName] = [
            'id'   => $id,
            'name' => '@' . strtolower($developerName),
        ];
    }

    /**
     * @Given :developerName has linked her slack user to :email
     */
    public function linkSlackUser(string $developerName, string $email)
    {
        $this->issueSlackCommand($developerName, "link to account $email");
    }

    /**
     * @Given there is a project named :name
     */
    public function createProject(string $name)
    {
        $response = $this->client->post(
            '/api/v1/projects',
            [
                'json' => ['name' => $name],
            ]
        );

        $id = $this->assertCreatedResponseAndGetId($response, '!^.*/api/v1/projects/(.*?)$!');

        $this->projects[$name] = ['id' => $id];
    }

    /**
     * @When :developer issues the command :command
     */
    public function issueSlackCommand(string $name, string $command)
    {
        assertArrayHasKey($name, $this->slackUsers);

        $slackUser = $this->slackUsers[$name];

        $response = $this->client->post(
            self::SLACK_ENDPOINT,
            [
                'form_params' => [
                    'token'        => 'gIkuvaNzQIHg97ATvDxqgjtO',
                    'team_id'      => 'T0001',
                    'team_domain'  => 'example',
                    'channel_id'   => 'C2147483705',
                    'channel_name' => 'test',
                    'user_id'      => $slackUser['id'],
                    'user_name'    => $slackUser['name'],
                    'command'      => '/tt',
                    'text'         => $command,
                    'response_url' => 'https://hooks.slack.com/commands/1234/5678',
                ],
            ]
        );

        assertSame(HttpStatus::STATUS_CREATED, $response->getStatusCode());
        $this->result = json_decode((string) $response->getBody());
    }

    /**
     * @Then :period hours should have been logged today by :developerName against :projectName for :description
     */
    public function assertTimeLoggedAgainstProject(
        Period $period,
        string $developerName,
        string $projectName,
        string $description
    ) {
        // Fetch front page
        $document = $this->jsonApiGet(self::REST_ENDPOINT);

        assertTrue($document->has('data.relationships.projects.links.related'));
        $link = $document->get('data.relationships.projects.links.related');

        // Fetch projects
        $document = $this->jsonApiGet($link);

        $resources = $document->get('data')->asArray();
        $project   = null;
        foreach ($resources as $resource) {
            if ($resource->get('attributes.name') === $projectName) {
                $project = $resource;
                break;
            }
        }

        assertNotNull($project, 'Project not found');
        assertSame('projects', $project->get('type'));
        assertTrue($project->has('links.self'), 'Failed to get links.self');

        $projectId = $project->get('id');
        $link      = $project->get('links.self');

        // Fetch project
        $document = $this->jsonApiGet($link);

        assertSame('projects', $document->get('data.type'));
        assertSame($projectName, $document->get('data.attributes.name'));

        $timeEntry = $document->get('included')->asArray()[0];
        assertSame('time-entries', $timeEntry->get('type'));

        $timeEntryObject = (object) [
            'projectId'   => $projectId,
            'developerId' => $timeEntry->get('relationships.developer.data.id'),
            'date'        => $timeEntry->get('attributes.date'),
            'period'      => $timeEntry->get('attributes.period'),
            'description' => $timeEntry->get('attributes.description'),
        ];

        $expectedEntry = (object) [
            'projectId'   => (string) $this->projects[$projectName]['id'],
            'developerId' => (string) $this->developers[$developerName]['id'],
            'date'        => (string) Date::today(),
            'period'      => (string) $period,
            'description' => $description,
        ];

        assertEquals($expectedEntry, $timeEntryObject);
    }

    /**
     * @Then :developerName should receive a response message saying :message
     */
    public function assertSlackResponseMessage(string $message)
    {
        assertSame('ephemeral', $this->result->response_type);
        assertSame($message, $this->result->text);
    }

    private function assertCreatedResponseAndGetId(Response $response, string $idRegex) : string
    {
        assertSame(HttpStatus::STATUS_CREATED, $response->getStatusCode());
        $locations = $response->getHeader('Location');
        assertCount(1, $locations, 'Exactly one location header should have been returned.');
        assertTrue(
            (bool) preg_match($idRegex, $locations[0], $matches),
            sprintf('Location "%s" does not match regex "%s".', $locations[0], $idRegex)
        );

        return $matches[1];
    }

    private function jsonApiGet(string $uri) : Document
    {
        $response = $this->client->get($uri);

        assertSame(HttpStatus::STATUS_OK, $response->getStatusCode());
        $json = (string) $response->getBody();

        return $this->jsonApiManager->parse($json);
    }
}
