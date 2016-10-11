<?php

namespace TomPHP\TimeTracker\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use TomPHP\TimeTracker\Api\Resources\ProjectResource;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;
use TomPHP\TimeTracker\Tracker\ProjectProjection;
use TomPHP\TimeTracker\Tracker\ProjectProjections;
use TomPHP\TimeTracker\Tracker\TimeEntryProjection;
use TomPHP\TimeTracker\Tracker\TimeEntryProjections;

final class ProjectsController
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postToCollection(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        $id = ProjectId::generate();
        Project::create($id, $params['name']);

        return $response->withJson([], HttpStatus::STATUS_CREATED)
            ->withHeader('Location', "/api/v1/projects/$id");
    }

    public function getCollection(Request $request, Response $response)
    {
        $projects = $this->container->get(ProjectProjections::class);

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
    }

    public function getResource(Request $request, Response $response, array $args)
    {
        $projects    = $this->container->get(ProjectProjections::class);
        $project     = $projects->withId(ProjectId::fromString($args['projectId']));
        $timeEntries = $this->container->get(TimeEntryProjections::class);

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

        $resource = new ProjectResource(
            (string) $project->id(),
            $project->name(),
            (string) $project->totalTime()
        );

        $json             = $resource->toJsonApiResource(apiUrl(''));
        $json['included'] = $timeEntries;

        return $response->withJson($json, HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/hal+json');
    }
}
