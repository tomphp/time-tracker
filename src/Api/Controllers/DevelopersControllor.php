<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Api\Controllers;

use Fig\Http\Message\StatusCodeInterface as HttpStatus;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use TomPHP\TimeTracker\Api\Resources\DeveloperResource;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Common\SlackHandle;
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
            Email::fromString($params['email']),
            SlackHandle::fromString($params['slack-handle'])
        );

        return $response->withJson([], HttpStatus::STATUS_CREATED)
            ->withHeader('Location', "/api/v1/developers/$id");
    }

    public function getResource(Request $request, Response $response, array $args)
    {
        $developers = $this->container->get(DeveloperProjections::class);

        $projection = $developers->withId(DeveloperId::fromString($args['developerId']));
        $resource   = new DeveloperResource(
            (string) $projection->id(),
            (string) $projection->name(),
            (string) $projection->slackHandle()
        );

        return $response->withJson(
            $resource->toJsonApiResource(apiUrl('')),
            HttpStatus::STATUS_OK
        );
    }
}
