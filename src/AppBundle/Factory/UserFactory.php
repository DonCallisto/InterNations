<?php

declare(strict_types=1);

namespace AppBundle\Factory;

use AppBundle\Entity\User;

class UserFactory
{
    public function create(string $username, string $email, string $plainPassword, string $name): User
    {
        return new User($username, $email, $plainPassword, $name);
    }
}
