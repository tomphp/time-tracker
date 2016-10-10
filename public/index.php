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

$app->group('/api/v1', function () {
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

    $this->get('/projects/{projectId}/time-entries', function (Request $request, Response $response, array $args) {
        $timeEntries = $this->get(TimeEntryProjections::class);

        $results = array_map(
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

        return $response->withJson($results, HttpStatus::STATUS_OK);
    });
});

$app->run();
