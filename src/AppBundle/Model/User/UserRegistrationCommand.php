<?php

declare(strict_types=1);

namespace AppBundle\Model\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class UserRegistrationCommand implements UserRegistrationCommandInterface
{
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

    public function setGroups(?ArrayCollection $groups)
    {
        $this->groups = $groups;
    }

    public function getGroups(): ?Collection
    {
        return $this->groups;
    }
}