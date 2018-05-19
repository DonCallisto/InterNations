<?php

declare(strict_types=1);

namespace AppBundle\API\DTO;

use AppBundle\Entity\Group;

class UserGroupDTO
{
    public $id;

    private $group;

    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }
}