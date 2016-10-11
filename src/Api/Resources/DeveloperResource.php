<?php

namespace TomPHP\TimeTracker\Api\Resources;

final class DeveloperResource
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $slackHandle;

    public function __construct(string $id, string $name, string $slackHandle)
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->slackHandle = $slackHandle;
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
            'type'       => 'developers',
            'id'         => $this->id,
            'attributes' => [
                'name'         => $this->name,
                'slack-handle' => $this->slackHandle,
            ],
        ];
    }

    public function linkSelf(string $baseUrl) : string
    {
        return $baseUrl . '/developers/' . $this->id;
    }
}
