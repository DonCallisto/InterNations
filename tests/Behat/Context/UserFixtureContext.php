<?php

declare(strict_types=1);

namespace InternationsBehat\Context;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;

class UserFixtureContext extends BaseContext
{
    /**
     * @Given /^There is an administrator user with "([^"]*)" username and "([^"]*)" password$/
     */
    public function createAdminUser(string $username, string $password)
    {
        $this->createUser($username, $password, 'foo@bar.com', ['ROLE_ADMIN']);
    }

    private function createUser(string $username, string $password, string $email, array $roles): User
    {
        $user = new User($username, $email, $password, 'foo');
        $user->setEnabled(true);
        $user->setRoles($roles);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Given /^There is user with "([^"]*)" username and "([^"]*)" password$/
     */
    public function createNotAdminUser(string $username, string $password)
    {
        $this->createUser($username, $password, 'foo@bar.com', ['ROLE_USER']);
    }

    /**
     * @Given /^There is a user with "([^"]*)" username and "([^"]*)" email$/
     */
    public function createUserFromEmail(string $username, string $email)
    {
        $this->createUser($username, 'password', $email, ['ROLE_USER']);
    }

    /**
     * @Given /^There is a user with "([^"]*)" username and "([^"]*)" email that is in a group$/
     */
    public function createUserFromEmailWithGroup(string $username, string $email)
    {
        $user = $this->createUser($username, 'password', $email, ['ROLE_USER']);
        $group = new Group('group');
        $group->addUser($user);

        $em = $this->getEntityManager();
        $em->persist($group);
        $em->flush();
    }

    /**
     * @Given /^There is a user with "([^"]*)" username and "([^"]*)" email in a group with "([^"]*)" name$/
     */
    public function createUserFromEmailWithSpecificGroup(string $username, string $email, string $groupName)
    {
        $user = $this->createUser($username, 'password', $email, ['ROLE_USER']);
        $group = new Group($groupName);
        $group->addUser($user);

        $em = $this->getEntityManager();
        $em->persist($group);
        $em->flush();
    }
}
