<?php

declare(strict_types=1);

namespace AppBundle\API\DTO;

use AppBundle\Model\User\UserRegistrationCommandInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User implements UserRegistrationCommandInterface
{
    public $id;

    public $username;

    public $password;

    public $email;

    public $name;

    private $groups;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setGroups(array $groups)
    {
        $this->groups = $groups;
    }

    public function getGroups(): ?Collection
    {
        return $this->groups ? new ArrayCollection($this->groups) : new ArrayCollection([]);
    }
}