<?php

declare(strict_types=1);

namespace InternationsBehat\Context;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;

class GroupFixtureContext extends BaseContext
{
    /**
     * @Given /^There is a group with "([^"]*)" name and no users$/
     */
    public function createGroupWithoutUsers(string $name)
    {
        $group = $this->createGroup($name);

        $em = $this->getEntityManager();
        $em->persist($group);
        $em->flush();
    }

    private function createGroup(string $name): Group
    {
        $group = new Group($name);

        return $group;
    }

    /**
     * @Given /^There is a group with "([^"]*)" name and at least one user$/
     */
    public function createGroupWithUsers(string $name)
    {
        $group = $this->createGroup($name);
        $user = new User('username', 'email', 'pwd', 'name');
        $group->addUser($user);

        $em = $this->getEntityManager();
        $em->persist($group);
        $em->persist($user);
        $em->flush();
    }
}