<?php declare(strict_types=1);

namespace TomPHP\TimeTracker\Tracker\Storage;

use TomPHP\TimeTracker\Common\DeveloperId;
use TomPHP\TimeTracker\Common\Email;
use TomPHP\TimeTracker\Tracker\DeveloperProjection;
use TomPHP\TimeTracker\Tracker\DeveloperProjections;

final class MySQLDeveloperProjectionRepository implements DeveloperProjections
{
    use MySQLTools;

    const TABLE_NAME = 'developer_projections';

    public function add(DeveloperProjection $developer)
    {
        $this->insert($developer);
    }

    public function all() : array
    {
        return $this->selectAll();
    }

    public function withId(DeveloperId $id) : DeveloperProjection
    {
        return $this->selectOne('id', (string) $id);
    }

    public function withEmail(Email $email) : DeveloperProjection
    {
        return $this->selectOne('email', (string) $email);
    }

    /**
     * @param DeveloperProjection $projection
     */
    protected function extract($projection) : array
    {
        return [
            'id'    => $projection->id(),
            'name'  => $projection->name(),
            'email' => $projection->email(),
        ];
    }

    /** @return DeveloperProjection */
    protected function create(\stdClass $fields)
    {
        return new DeveloperProjection(
            DeveloperId::fromString($fields->id),
            $fields->name,
            Email::fromString($fields->email)
        );
    }
}
