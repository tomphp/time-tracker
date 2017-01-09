<?php declare(strict_types=1);

namespace test\features\TomPHP\TimeTracker;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Slim\Container;
use TomPHP\Siren;
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

    /** @var SlackUserId[] */
    private $slackUsers = [];

    /** @var array|\stdClass */
    private $result;

    /** @var string */
    private $slackToken;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri'        => getenv('SITE_URL'),
            'allow_redirects' => true,
            'auth'            => ['admin', getenv('ADMIN_PASSWORD')],
        ]);

        $services = new Container();

        Bootstrap::run($services);

        $this->slackToken = $services->get('config.slack.token');
    }

    /**
     * @Given :name has a developer account with email :email
     */
    public function fetchDeveloperWithEmail(string $name, string $email)
    {
        $developer = $this->findOrCreateEntity(
            'developers',
            'add-developer',
            'email',
            ['name' => $name, 'email' => $email]
        );

        $this->developers[$name] = ['id' => $developer->getProperty('id')];
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
            'name' => '@' . mb_strtolower($developerName),
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
        $project = $this->findOrCreateEntity('projects', 'add-project', 'name', ['name' => $name]);

        $this->projects[$name] = [
            'id'         => $project->getProperty('id'),
            'total_time' => Period::fromString($project->getProperty('total_time')),
        ];
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
                    'token'        => $this->slackToken,
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
        $project = $this->findEntityByProperty('projects', 'name', $projectName);

        $projectLink = $project->getLinksByRel('self')[0];
        $document    = $this->apiGet($projectLink->getHref());

        $totalTime = Period::fromString($document->getProperty('total_time'));
        $timeDelta = $totalTime->subtract($this->projects[$projectName]['total_time']);

        assertEquals($period, $timeDelta);

        $entities = $document->getEntities();
        $timeEntry = array_pop($entities);

        $timeEntryObject = (object) [
            //'developerId' => $timeEntry->getEntity('developer')->getHref(), <- OUCH
            'date'        => $timeEntry->getProperty('date'),
            'period'      => $timeEntry->getProperty('period'),
            'description' => $timeEntry->getProperty('description'),
        ];

        $expectedEntry = (object) [
            //'developerId' => (string) $this->developers[$developerName]['id'],
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

    private function findEntityByProperty(string $collectionName, string $property, $value) : Siren\Entity
    {
        $collection = $this->getCollection($collectionName);
        $found      = $collection->getEntitiesByProperty($property, $value);

        assertCount(1, $found, "Expected exactly 1 entity to be found in $collectionName with $property == $value");

        return $found[0];
    }

    private function findOrCreateEntity(
        string $collection,
        string $actionName,
        string $idProperty,
        array $params
    ) : Siren\Entity {
        $collection = $this->getCollection($collection);

        $found    = $collection->getEntitiesByProperty($idProperty, $params[$idProperty]);
        $numFound = count($found);

        switch ($numFound) {
            case 0:
                $action = $collection->getAction($actionName);
                $entity = $this->performAction($action, $params);
                break;

            case 1:
                $entity = $found[0];
                break;

            default:
                throw new \Exception("Found $numFound $collection");
        }

        return $entity;
    }

    public function performAction(Siren\Action $action, array $fields = []) : Siren\Entity
    {
        $actionMethod = mb_strtolower($action->getMethod());
        $fields       = ['json' => $fields];

        $response = $this->client->$actionMethod($action->getHref(), $fields);

        assertSame(HttpStatus::STATUS_CREATED, $response->getStatusCode());

        $link     = $response->getHeader('location')[0];
        $response = $this->client->get($link);

        assertSame(HttpStatus::STATUS_OK, $response->getStatusCode());
        var_dump((string) $response->getBody());
        assertContains('application/vnd.siren+json', $response->getHeader('content-type'));
        $json = (string) $response->getBody();

        return Siren\Entity::fromArray(json_decode($json, true));
    }

    private function getCollection(string $name) : Siren\Entity
    {
        $entryPoint = $this->apiGet(self::REST_ENDPOINT);

        return $this->apiGet($entryPoint->getLinksByClass($name)[0]->getHref());
    }

    private function apiGet(string $uri)
    {
        $response = $this->client->get($uri);

        assertSame(HttpStatus::STATUS_OK, $response->getStatusCode());
        assertContains('application/vnd.siren+json', $response->getHeader('content-type'));
        $json = (string) $response->getBody();

        return Siren\Entity::fromArray(json_decode($json, true));
    }
}
