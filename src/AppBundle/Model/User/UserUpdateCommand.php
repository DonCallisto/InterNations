<?php

declare(strict_types=1);

namespace AppBundle\Model\User;

use AppBundle\Entity\User;

class UserUpdateCommand
{
    public $password;

    public $email;

    public $name;

    public $groups;

    public static function createFromUser(User $user): self
    {
        $command = new self();
        $command->email = $user->getEmail();
        $command->name = $user->getName();
        $command->groups = $user->getGroups();

        return $command;
    }
}