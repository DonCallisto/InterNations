<?php

declare(strict_types=1);

namespace InternationsBehat\Context\Ui\User;

use AppBundle\Entity\User;
use InternationsBehat\Context\Ui\BaseUiContext;
use InternationsBehat\Page\User\ListPage;
use Webmozart\Assert\Assert;

class DeleteContext extends BaseUiContext
{
    private $listPage;

    public function __construct(ListPage $listPage)
    {
        $this->listPage = $listPage;
    }

    /**
     * @When /^I try to create delete user with "([^"]*)" username$/
     */
    public function tryDeleteUser(string $username)
    {
        $this->listPage->open();
        $this->listPage->deleteUser($username);
    }

    /**
     * @Then /^User with "([^"]*)" username should be deleted$/
     */
    public function userShouldBeDeleted(string $username)
    {
        $user = $this->findUser($username);

        Assert::true($this->listPage->isOpen(), 'User list page is not open');
        Assert::null($user, 'User not deleted!');
        Assert::true($this->listPage->isUserDeleteMessageShown(), 'User message not shown');
    }

    /**
     * @Then /^I should not be allowed to delete user$/
     */
    public function userShouldNotBeAllowedToBeDeleted()
    {
        Assert::eq($this->getSession()->getStatusCode(), 403, 'User deleted!');
    }

    /**
     * @Then /^I should not be allowed to delete user with "([^"]*)" username$/
     */
    public function userShouldNotBeAllowedToDeleteHimself(string $username)
    {
        $user = $this->findUser($username);

        Assert::true($this->listPage->isOpen(), 'User list page is not open');
        Assert::isInstanceOf($user, User::class, 'User deleted');
        Assert::true($this->listPage->isUserDeleteHimselfMessageShown(), 'User message not shown');
    }
}