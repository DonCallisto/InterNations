<?php

namespace Tests\PHPSpec\AppBundle\Factory;

use AppBundle\Entity\User;
use PhpSpec\ObjectBehavior;

class UserFactorySpec extends ObjectBehavior
{
    public function it_should_create_a_user()
    {
        $this
            ->create('username', 'email', 'plainPassword', 'name')
            ->shouldBeAnInstanceOf(User::class);
    }
}
