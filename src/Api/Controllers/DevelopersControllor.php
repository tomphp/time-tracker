<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use TomPHP\Siren;
use TomPHP\TimeTracker\Api\Resources\DeveloperResource;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Tracker\Developer;
use TomPHP\TimeTracker\Tracker\DeveloperId;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

final class DevelopersControllor
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postToCollection($request, $response)
    {
        $params = $request->getParsedBody();

        $id = DeveloperId::generate();
        Developer::create(
            $id,
            $params['name'],
            Email::fromString($params['email'])
        );

        return $response->withJson([], HttpStatus::STATUS_CREATED)
            ->withHeader('Content-Type', 'application/vnd.siren+json')
            ->withHeader('Location', "/api/v1/developers/$id");
    }

    public function getCollection(Request $request, Response $response)
    {
        $builder = Siren\Entity::builder()
            ->addLink('self', apiUrl('/developers'))
            ->addClass('developer');

        $developers = $this->container->get(DeveloperProjections::class);

        foreach ($developers->all() as $developer) {
            $projectEntity = Siren\Entity::builder()
                ->addLink('self', apiUrl('/developers/' . $developer->id()))
                ->addProperty('id', (string) $developer->id())
                ->addProperty('name', (string) $developer->name())
                ->addProperty('email', (string) $developer->email())
                ->addClass('developer')
                ->build();

            $builder->addSubEntity($projectEntity);
        }

        $addProject = Siren\Action::builder()
            ->setName('add-developer')
            ->setHref(apiUrl('/developers'))
            ->setMethod('POST')
            ->setTitle('Add Developer')
            ->addClass('developer')
            //->setType('application/json')
            ->build();

        $builder->addAction($addProject);
        $collection = $builder->build();

        return $response->withJson($collection->toArray(), HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/vnd.siren+json');
    }

    public function getResource(Request $request, Response $response, array $args)
    {
        $developers = $this->container->get(DeveloperProjections::class);

        $projection = $developers->withId(DeveloperId::fromString($args['developerId']));

        $project = Siren\Entity::builder()
            ->addLink('self', apiUrl('/developers/' . $projection->id()))
            ->addProperty('id', (string) $projection->id())
            ->addProperty('name', (string) $projection->name())
            ->addProperty('email', (string) $projection->email())
            ->addClass('developer')
            ->build();

        return $response->withJson($project->toArray(), HttpStatus::STATUS_OK)
            ->withHeader('Content-Type', 'application/vnd.siren+json');
    }
}
