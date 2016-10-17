<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Api\Resources;

use TomPHP\TimeTracker\Api\Resources\DeveloperCollection;
use TomPHP\TimeTracker\Api\Resources\DeveloperResource;

final class DeveloperCollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_converts_to_a_json_api_data_structure()
    {
        $collection = new DeveloperCollection([
            new DeveloperResource('developer-1', 'Developer One', 'developer-slack-1'),
            new DeveloperResource('developer-2', 'Developer Two', 'developer-slack-2'),
        ]);

        assertEquals(
            [
                'links' => [
                    'self' => 'https://api.example.com/v1/developers',
                ],
                'data' => [
                    [
                        'type'       => 'developers',
                        'id'         => 'developer-1',
                        'attributes' => [
                            'name'         => 'Developer One',
                            'slack-handle' => 'developer-slack-1',
                        ],
                        'links' => [
                            'self' => 'https://api.example.com/v1/developers/developer-1',
                        ],
                    ],
                    [
                        'type'       => 'developers',
                        'id'         => 'developer-2',
                        'attributes' => [
                            'name'         => 'Developer Two',
                            'slack-handle' => 'developer-slack-2',
                        ],
                        'links' => [
                            'self' => 'https://api.example.com/v1/developers/developer-2',
                        ],
                    ],
                ],
            ],
            $collection->toJsonApiResource('https://api.example.com/v1')
        );
    }
}
