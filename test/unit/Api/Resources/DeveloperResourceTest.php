<?php

namespace test\unit\TomPHP\TimeTracker\Api\Resources;

use TomPHP\TimeTracker\Api\Resources\DeveloperResource;

final class DeveloperResourceTest extends \PHPUnit_Framework_TestCase
{
    /** @var DeveloperResource */
    private $resource;

    protected function setUp()
    {
        $this->resource = new DeveloperResource(
            'developer-id',
            'Developer Name',
            'developer-slack-handle'
        );
    }

    /** @test */
    public function it_converts_to_a_json_api_data_structure()
    {
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
            $this->resource->toJsonApiResource('https://api.example.com/v1')
        );
    }

    /** @test */
    public function it_returns_its_data_block()
    {
        assertEquals(
            [
                'type'       => 'developers',
                'id'         => 'developer-id',
                'attributes' => [
                    'name'         => 'Developer Name',
                    'slack-handle' => 'developer-slack-handle',
                ],
            ],
            $this->resource->data()
        );
    }

    /** @test */
    public function it_returns_it_self_link()
    {
        assertSame(
            'https://api.example.com/v1/developers/developer-id',
            $this->resource->linkSelf('https://api.example.com/v1')
        );
    }
}