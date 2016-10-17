<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Api\Resources;

final class DeveloperCollection
{
    /** @var DeveloperResource[] */
    private $developers;

    /** @param DeveloperResource[] $developers */
    public function __construct(array $developers)
    {
        $this->developers = $developers;
    }

    public function toJsonApiResource(string $baseUrl) : array
    {
        $developers = array_map(
            function (DeveloperResource $developer) use ($baseUrl) {
                $result = array_merge(
                    $developer->data(),
                    [
                        'links' => [
                            'self' => $developer->linkSelf($baseUrl),
                        ],
                    ]
                );

                return $result;
            },
            $this->developers
        );

        return [
            'links' => [
                'self' => $baseUrl . '/developers' . $this->id,
            ],
            'data' => $developers,
        ];
    }
}
