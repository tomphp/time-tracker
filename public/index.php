<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use TomPHP\Siren;
use TomPHP\TimeTracker\Api\Controllers\DevelopersControllor;
use TomPHP\TimeTracker\Api\Controllers\ProjectsController;
use TomPHP\TimeTracker\Bootstrap;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\SlackUserId;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

const PROJECT_ROOT = __DIR__ . '/..';

header('Access-Control-Allow-Origin: *');

require PROJECT_ROOT . '/vendor/autoload.php';

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

Bootstrap::run($app->getContainer());

$app->group('/slack', function () {
    $this->post('/slash-command-endpoint', function (Request $request, Response $response) {
        $params = $request->getParsedBody();
        $token = $params['token'];

        if ($token !== $this->get('config.slack.token')) {
            return $response->withJson([], HttpStatus::STATUS_FORBIDDEN);
        }

        $result = $this->get(CommandRunner::class)
            ->run(SlackUserId::fromString($params['user_id']), $params['text']);

        return $response->withJson($result, HttpStatus::STATUS_CREATED);
    });
});

function apiUrl(string $path) : string
{
    return 'http://' . $_SERVER['HTTP_HOST'] . '/api/v1' . $path;
}

$app->group('/api/v1', function () {
    $this->get('', function (Request $request, Response $response) {
        $document = Siren\Entity::builder()
            ->addLink('collection', apiUrl('/projects'), 'projects')
            ->addLink('collection', apiUrl('/developers'), 'developers')
            ->addClass('index')
            ->build();

        return $response->withJson($document->toArray(), HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/vnd.siren+json');
    });

    $this->post('/developers', DevelopersControllor::class . ':postToCollection');
    $this->get('/developers', DevelopersControllor::class . ':getCollection');
    /* Untested */
    $this->get('/developers/{developerId}', DevelopersControllor::class . ':getResource');

    $this->post('/projects', ProjectsController::class . ':postToCollection');
    $this->get('/projects', ProjectsController::class . ':getCollection');
    $this->get('/projects/{projectId}', ProjectsController::class . ':getResource');

    /*
    $this->get('/projects/{projectId}/time-entries', function (Request $request, Response $response, array $args) {
        $timeEntries = $this->get(TimeEntryProjections::class);

        $timeEntries = array_map(
            function (TimeEntryProjection $timeEntry) {
                return [
                    'projectId'   => (string) $timeEntry->projectId(),
                    'developerId' => (string) $timeEntry->developerId(),
                    'date'        => (string) $timeEntry->date(),
                    'period'      => (string) $timeEntry->period(),
                    'description' => $timeEntry->description(),
                ];
            },
            $timeEntries->forProject(ProjectId::fromString($args['projectId']))
        );

        return $response->withJson($timeEntries, HttpStatus::STATUS_OK);
    });
     */
});

$app->run();
