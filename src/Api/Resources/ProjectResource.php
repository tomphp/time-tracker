<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Api\Resources;

final class ProjectResource
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $totalTime;

    public function __construct(string $id, string $name, string $totalTime)
    {
        $this->id        = $id;
        $this->name      = $name;
        $this->totalTime = $totalTime;
    }

    public function toJsonApiResource(string $baseUrl) : array
    {
        return [
            'links' => [
                'self' => $this->linkSelf($baseUrl),
            ],
            'data' => $this->data(),
        ];
    }

    public function data() : array
    {
        return [
            'type'       => 'projects',
            'id'         => $this->id,
            'attributes' => [
                'name'       => $this->name,
                'total-time' => $this->totalTime,
            ],
        ];
    }

    public function linkSelf(string $baseUrl) : string
    {
        return $baseUrl . '/projects/' . $this->id;
    }
}
