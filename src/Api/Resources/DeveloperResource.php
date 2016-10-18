<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Api\Resources;

final class DeveloperResource
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $email;

    public function __construct(string $id, string $name, string $email)
    {
        $this->id    = $id;
        $this->name  = $name;
        $this->email = $email;
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
                'name'  => $this->name,
                'email' => $this->email,
            ],
        ];
    }

    public function linkSelf(string $baseUrl) : string
    {
        return $baseUrl . '/developers/' . $this->id;
    }
}
