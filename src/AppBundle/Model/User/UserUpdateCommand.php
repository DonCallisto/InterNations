<?php

declare(strict_types=1);

namespace AppBundle\Model\User;

use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateCommand
{
    public $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @Assert\Collection()
     */
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