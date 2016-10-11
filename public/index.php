<?php declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Common\SlackHandle;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\EventBus;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

const PROJECT_ROOT = __DIR__ . '/..';

require PROJECT_ROOT . '/vendor/autoload.php';

$app = new App([]);

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

        $this->get(CommandRunner::class)->run(SlackHandle::fromString($params['user_name']), $params['text']);

        $result = [
            'response_type' => 'ephemeral',
            'text'          => 'Your time has been logged.',
        ];

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

    $this->post('/developers', function (Request $request, Response $response) {
        $params = $request->getParsedBody();

        $id = DeveloperId::generate();
        Developer::create($id, $params['name'], SlackHandle::fromString($params['slack-handle']));

        return $response->withJson([], HttpStatus::STATUS_CREATED)
            ->withHeader('Location', "/api/v1/developers/$id");
    });

    $this->post('/projects', function (Request $request, Response $response) {
        $params = $request->getParsedBody();

        $id = ProjectId::generate();
        Project::create($id, $params['name']);

        return $response->withJson([], HttpStatus::STATUS_CREATED)
            ->withHeader('Location', "/api/v1/projects/$id");
    });

    $this->get('/projects', function (Request $request, Response $response) {
        $projects = $this->get(ProjectProjections::class);

        $formattedProjects = array_map(
            function (ProjectProjection $project) use ($request) {
                return [
                    'type'       => 'projects',
                    'id'         => (string) $project->id(),
                    'attributes' => [
                        'name'       => $project->name(),
                        'total-time' => (string) $project->totalTime(),
                    ],
                    'links' => [
                        'self' => apiUrl('/projects/' . $project->id()),
                    ],
                ];
            },
            $projects->all()
        );

        $result = [
            'links' => [
                'self' => apiUrl('/projects'),
            ],
            'data' => $formattedProjects,
        ];

        return $response->withJson($result, HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/hal+json');
    });

    $this->get('/projects/{projectId}', function (Request $request, Response $response, array $args) {
        $projects    = $this->get(ProjectProjections::class);
        $project     = $projects->withId(ProjectId::fromString($args['projectId']));
        $timeEntries = $this->get(TimeEntryProjections::class);

        $timeEntries = array_map(
            function (TimeEntryProjection $timeEntry) {
                return [
                    'type'       => 'time-entries',
                    'id'         => 'missing-time-entry-id',
                    'attributes' => [
                        'date'        => (string) $timeEntry->date(),
                        'period'      => (string) $timeEntry->period(),
                        'description' => $timeEntry->description(),
                    ],
                    'relationships' => [
                        'developer' => [
                            'data' => [
                                'type' => 'developers',
                                'id'   => (string) $timeEntry->developerId(),
                            ],
                            'links' => [
                                'self' => apiUrl('/developers/' . $timeEntry->developerId()),
                            ],
                        ],
                    ],
                ];
            },
            $timeEntries->forProject(ProjectId::fromString($args['projectId']))
        );

        $result = [
            'links' => [
                'self' => apiUrl('/projects/' . $args['projectId']),
            ],
            'data' => [
                'type'         => 'projects',
                'id'           => (string) $project->id(),
                'attributes'   => [
                    'name'         => $project->name(),
                    'total-time'   => (string) $project->totalTime(),
                ],
            ],
            'included' => $timeEntries,
        ];

        return $response->withJson($result, HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/hal+json');
    });

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
