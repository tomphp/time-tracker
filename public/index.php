<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Api\Controllers\DevelopersControllor;
use TomPHP\TimeTracker\Api\Controllers\ProjectsController;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Slack\SlackUserId;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\EventBus;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

const PROJECT_ROOT = __DIR__ . '/..';

require PROJECT_ROOT . '/vendor/autoload.php';

$app = new App([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);

Configurator::apply()
    ->configFromFiles(PROJECT_ROOT . '/config/*.global.php')
    ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
    ->to($app->getContainer());

foreach ($app->getContainer()->get('config.tracker.event_handlers') as $name) {
    EventBus::addHandler($app->getContainer()->get($name));
}

$app->group('/slack', function () {
    $this->post('/slash-command-endpoint', function (Request $request, Response $response) {
        $params = $request->getParsedBody();

        error_log('Slack name = ' . $params['user_name']);
        error_log('Command    = ' . $params['command']);
        error_log('Text       = ' . $params['text']);
        error_log(print_r($params, true));

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
        $result = [
            'links' => [
                'self' => apiUrl(''),
            ],
            'data' => [
                'type'          => 'font-page',
                'id'            => '1',
                'relationships' => [
                    'projects' => [
                        'links' => [
                            'related' => apiUrl('/projects'),
                        ],
                    ],
                ],
            ],
        ];

        return $response->withJson($result, HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/vnd.api+json');
    });

    $this->post('/developers', DevelopersControllor::class . ':postToCollection');
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
