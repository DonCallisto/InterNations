<?php

declare(strict_types=1);

namespace AppBundle\Factory;

use AppBundle\Entity\Group;

class GroupFactory
{
    public function create(string $name): Group
    {
        return new Group($name);
    }
}
