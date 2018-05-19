<?php

declare(strict_types=1);

namespace Tests\PHPSpec\AppBundle\Model\User;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;

class UserUpdateCommandSpec extends ObjectBehavior
{
    public function it_creates_an_update_command_based_on_a_user(User $user, Group $g1, Group $g2)
    {
        $email = 'email';
        $name = 'name';
        $groups = new ArrayCollection([
            $g1,
            $g2
        ]);

        $user->getEmail()->willReturn($email);
        $user->getName()->willReturn($name);
        $user->getGroups()->willReturn($groups);

        $command = $this::createFromUser($user);

        $command->email->shouldBeEqualTo($email);
        $command->name->shouldBeEqualTo($name);
        $command->groups->shouldBeEqualTo($groups);
    }
}