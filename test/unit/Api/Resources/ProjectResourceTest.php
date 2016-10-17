<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Api\Resources;

use TomPHP\TimeTracker\Api\Resources\ProjectResource;
use TomPHP\TimeTracker\Common\Period;

final class ProjectResourceTest extends \PHPUnit_Framework_TestCase
{
    /** @var ProjectResource */
    private $resource;

    protected function setUp()
    {
        $this->resource = new ProjectResource(
            'project-id',
            'Project Name',
            (string) Period::fromString('2')
        );
    }

    /** @test */
    public function it_converts_to_a_json_api_data_structure()
    {
        assertEquals(
            [
                'links' => [
                    'self' => 'https://api.example.com/v1/projects/project-id',
                ],
                'data' => [
                    'type'       => 'projects',
                    'id'         => 'project-id',
                    'attributes' => [
                        'name'       => 'Project Name',
                        'total-time' => '2:00 hours',
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
                'type'       => 'projects',
                'id'         => 'project-id',
                'attributes' => [
                    'name'       => 'Project Name',
                    'total-time' => '2:00 hours',
                ],
            ],
            $this->resource->data()
        );
    }

    /** @test */
    public function it_returns_it_self_link()
    {
        assertSame(
            'https://api.example.com/v1/projects/project-id',
            $this->resource->linkSelf('https://api.example.com/v1')
        );
    }
}
