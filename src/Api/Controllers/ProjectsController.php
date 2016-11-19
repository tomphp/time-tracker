<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use TomPHP\Siren;
use TomPHP\TimeTracker\Tracker\Project;
use TomPHP\TimeTracker\Tracker\ProjectId;
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
        $builder = Siren\Entity::builder()
            ->addLink('self', apiUrl('/projects'))
            ->addClass('project');

        $projects = $this->container->get(ProjectProjections::class);

        foreach ($projects->all() as $project) {
            $projectEntity = Siren\Entity::builder()
                ->addLink('self', apiUrl('/projects/' . $project->id()))
                ->addProperty('name', (string) $project->name())
                ->addProperty('total_time', (string) $project->totalTime())
                ->addClass('project')
                ->build();

            $builder->addSubEntity($projectEntity);
        }

        $addProject = Siren\Action::builder()
            ->setName('add-project')
            ->setHref(apiUrl('/projects'))
            ->setMethod('POST')
            ->setTitle('Add Project')
            ->addClass('project')
            //->setType('application/json')
            ->build();

        $builder->addAction($addProject);
        $collection = $builder->build();

        return $response->withJson($collection->toArray(), HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/vnd.siren+json');
    }

    public function getResource(Request $request, Response $response, array $args)
    {
        $projects    = $this->container->get(ProjectProjections::class);
        $project     = $projects->withId(ProjectId::fromString($args['projectId']));
        $timeEntries = $this->container->get(TimeEntryProjections::class);

        $timeEntries = array_map(
            function (TimeEntryProjection $timeEntry) {
                $developer = new Siren\EntityLink(
                    ['developer'],
                    apiUrl('/developers/' . $timeEntry->developerId())
                );

                $timeEntryEntity = Siren\Entity::builder()
                    ->addLink('self', apiUrl('/projects/' . $timeEntry->projectId() . '/time-entries/' . $timeEntry->id()))
                    ->addProperty('date', (string) $timeEntry->date())
                    ->addProperty('period', (string) $timeEntry->period())
                    ->addProperty('description', $timeEntry->description())
                    ->addSubEntity($developer)
                    ->addClass('time-entry')
                    ->build();

                return $timeEntryEntity;
            },
            $timeEntries->forProject(ProjectId::fromString($args['projectId']))
        );

        $builder = Siren\Entity::builder()
            ->addLink('self', apiUrl('/projects/' . $project->id()))
            ->addProperty('name', $project->name())
            ->addProperty('total_time', (string) $project->totalTime())
            ->addClass('project');

        foreach ($timeEntries as $timeEntry) {
            $builder->addSubEntity($timeEntry);
        }

        $entity = $builder->build();

        return $response->withJson($entity->toArray(), HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/vnd.siren+json');
    }
}
