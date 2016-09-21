<?php declare(strict_types=1);

namespace test\unit\TomPHP\TimeTracker\Domain;

use Ramsey\Uuid\Uuid;
use TomPHP\TimeTracker\Domain\ProjectId;

final class ProjectIdTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function on_toString_it_returns_a_string_representation()
    {
        $string = 'dae81576-11c9-4a99-96da-0d1901c337d0';
        $id     = new ProjectId(Uuid::fromString($string));

        assertSame($string, (string) $id);
    }

    /** @test */
    public function on_generate_it_creates_a_unique_id()
    {
        $id1 = ProjectId::generate();
        $id2 = ProjectId::generate();

        assertNotEquals($id1, $id2);
    }
}
