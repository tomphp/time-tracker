<?php

namespace test\unit\TomPHP\TimeTracker\Api\Resources;

use TomPHP\TimeTracker\Api\Resources\DeveloperResource;

final class DeveloperResourceTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_converts_to_a_json_api_data_structure()
    {
        $resource = new DeveloperResource('developer-id', 'Developer Name', 'developer-slack-handle');

        assertEquals(
            [
                'links' => [
                    'self' => 'https://api.example.com/v1/developers/developer-id',
                ],
                'data' => [
                    'type'       => 'developers',
                    'id'         => 'developer-id',
                    'attributes' => [
                        'name'         => 'Developer Name',
                        'slack-handle' => 'developer-slack-handle',
                    ],
                ],
            ],
            $resource->toJsonApiResource('https://api.example.com/v1')
        );
    }

    /** @test */
    public function it_returns_its_data_block()
    {
        $resource = new DeveloperResource('developer-id', 'Developer Name', 'developer-slack-handle');

        assertEquals(
            [
                'type'       => 'developers',
                'id'         => 'developer-id',
                'attributes' => [
                    'name'         => 'Developer Name',
                    'slack-handle' => 'developer-slack-handle',
                ],
            ],
            $resource->data()
        );
    }

    /** @test */
    public function it_returns_it_self_link()
    {
        $resource = new DeveloperResource('developer-id', 'Developer Name', 'developer-slack-handle');

        assertSame(
            'https://api.example.com/v1/developers/developer-id',
            $resource->linkSelf('https://api.example.com/v1')
        );
    }
}
