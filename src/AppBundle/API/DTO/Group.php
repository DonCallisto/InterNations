<?php

declare(strict_types=1);

namespace AppBundle\API\DTO;

use AppBundle\Model\Group\GroupCreationCommandInterface;
use AppBundle\Model\Group\GroupDeleteCommandInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Group implements GroupCreationCommandInterface, GroupDeleteCommandInterface
{
    public $id;

    public $name;

    public $users;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setUsers(array $users)
    {
        $this->users = $users;
    }

    public function getUsers(): ?Collection
    {
        return $this->users ? new ArrayCollection($this->users) : new ArrayCollection([]);
    }
}