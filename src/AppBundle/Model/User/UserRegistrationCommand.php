<?php

declare(strict_types=1);

namespace AppBundle\Model\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationCommand
{
    /**
     * @Assert\NotBlank()
     */
    public $username;

    /**
     * @Assert\NotBlank()
     */
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
}