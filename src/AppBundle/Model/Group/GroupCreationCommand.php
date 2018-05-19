<?php

declare(strict_types=1);

namespace AppBundle\Model\Group;

class GroupCreationCommand implements GroupCreationCommandInterface
{
    public $name;

    public function getName(): ?string
    {
        return $this->name;
    }
}