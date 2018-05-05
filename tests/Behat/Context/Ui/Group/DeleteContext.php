<?php

declare(strict_types=1);

namespace InternationsBehat\Context\Ui\Group;

use AppBundle\Entity\Group;
use InternationsBehat\Context\Ui\BaseUiContext;
use InternationsBehat\Page\Group\ListPage;
use Webmozart\Assert\Assert;

class DeleteContext extends BaseUiContext
{
    private $listPage;

    public function __construct(ListPage $listPage)
    {
        $this->listPage = $listPage;
    }

    /**
     * @When /^I try to delete group with "([^"]*)" name$/
     */
    public function tryDeleteGroup(string $name)
    {
        $this->listPage->open();
        $this->listPage->deleteGroup($name);
    }

    /**
     * @Then /^Group with "([^"]*)" name should be deleted$/
     */
    public function groupShouldBeDeleted(string $name)
    {
        $group = $this->findGroup($name);

        Assert::true($this->listPage->isOpen(), 'Group list page is not open');
        Assert::null($group, 'Group not deleted!');
        Assert::true($this->listPage->isGroupDeleteMessageShown(), 'Group message not shown');
    }

    /**
     * @Then /^Group with "([^"]*)" name should not be deleted$/
     */
    public function groupShouldNotBeDeleted(string $name)
    {
        $group = $this->findGroup($name);

        Assert::true($this->listPage->isOpen(), 'Group list page is not open');
        Assert::isInstanceOf($group, Group::class, 'Group deleted!');
        Assert::true($this->listPage->isGroupCannoteDeleteDueToUsersMessageShown(), 'Group message not shown');
    }

    /**
     * @Then /^I should not be allowed to delete group/
     */
    public function groupShouldNotBeAllowedToBeDeleted()
    {
        Assert::eq($this->getSession()->getStatusCode(), 403, 'Group deleted!');
    }
}