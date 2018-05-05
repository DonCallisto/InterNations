<?php

namespace Tests\PHPSpec\AppBundle\Factory;

use AppBundle\Entity\Group;
use PhpSpec\ObjectBehavior;

class GroupFactorySpec extends ObjectBehavior
{
    public function it_should_create_a_group()
    {
        $this
            ->create('name')
            ->shouldBeAnInstanceOf(Group::class);
    }
}
