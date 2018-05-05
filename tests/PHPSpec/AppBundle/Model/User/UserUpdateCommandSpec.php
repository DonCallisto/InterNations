<?php

declare(strict_types=1);

namespace Tests\PHPSpec\AppBundle\Model\User;

use AppBundle\Entity\User;
use AppBundle\Model\User\UserUpdateCommand;
use PhpSpec\ObjectBehavior;

class UserUpdateCommandSpec extends ObjectBehavior
{
    public function it_creates_an_update_command_based_on_a_user(User $user, UserUpdateCommand $command)
    {
        $email = 'email';
        $name = 'name';

        $user->getEmail()->willReturn($email);
        $user->getName()->willReturn($name);

        $command = $this::createFromUser($user);

        $command->email->shouldBeEqualTo($email);
        $command->name->shouldBeEqualTo($name);
    }
}