<?php declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use TomPHP\ContainerConfigurator\Configurator;
use TomPHP\TimeTracker\Slack\CommandRunner;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;
use TomPHP\TimeTracker\Common\SlackHandle;

const PROJECT_ROOT = __DIR__ . '/..';

require PROJECT_ROOT . '/vendor/autoload.php';

$app = new App([]);

Configurator::apply()
    ->configFromFiles(PROJECT_ROOT . '/config/*')
    ->configFromArray([
        'di' => [
            'services' => [
                CommandRunner::class => [
                    'arguments' => [$app->getContainer(), 'config.slack.commands'],
                ],
            ],
        ],
    ])
    ->withSetting(Configurator::SETTING_DEFAULT_SINGLETON_SERVICES, true)
    ->to($app->getContainer());

$app->group('/slack', function () {
    $this->post('/slash-command-endpoint', function (Request $request, Response $response) {
        $params = $request->getParsedBody();

        error_log('Slack name = ' . $params['user_name']);
        error_log('Command    = ' . $params['command']);

        list($slash, $command) = explode(' ', $params['command'], 2);

        $this->get(CommandRunner::class)->run(SlackHandle::fromString($params['user_name']), $command);
    });
});

$app->group('/api/v1', function () {
    $this->get('/projects/{projectId}/time-entries', function (Request $request, Response $response, array $args) {
        $timeEntries = $this->get(TimeEntryProjections::class);

        $results = array_map(
            function (TimeEntryProjection $timeEntry) {
                return [
                    'projectId'   => $timeEntry->projectId(),
                    'developerId' => $timeEntry->developerId(),
                    'date'        => $timeEntry->date(),
                    'period'      => $timeEntry->period(),
                    'description' => $timeEntry->description(),
                ];
            },
            $timeEntries->forProject(ProjectId::fromString($args['projectId']))
        );

        return $response->getBody()->write(json_encode($results));
    });
});

$app->run();
