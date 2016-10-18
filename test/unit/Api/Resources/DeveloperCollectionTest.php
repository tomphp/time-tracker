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
            new DeveloperResource('developer-1', 'Developer One', 'developer1@email.com'),
            new DeveloperResource('developer-2', 'Developer Two', 'developer2@email.com'),
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
                            'name'  => 'Developer One',
                            'email' => 'developer1@email.com',
                        ],
                        'links' => [
                            'self' => 'https://api.example.com/v1/developers/developer-1',
                        ],
                    ],
                    [
                        'type'       => 'developers',
                        'id'         => 'developer-2',
                        'attributes' => [
                            'name'  => 'Developer Two',
                            'email' => 'developer2@email.com',
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
