<?php

declare(strict_types=1);

namespace InternationsBehat\Context\Ui\Group;

use AppBundle\Entity\Group;
use InternationsBehat\Context\Ui\BaseUiContext;
use InternationsBehat\Page\Group\CreatePage;
use InternationsBehat\Page\Group\ListPage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;
use Webmozart\Assert\Assert;

class CreateContext extends BaseUiContext
{
    const GROUP_NAME = 'aGroup';

    private $createPage;

    private $listPage;

    public function __construct(CreatePage $createPage, ListPage $listaPage)
    {
        $this->createPage = $createPage;
        $this->listPage = $listaPage;
    }

    /**
     * @When /^I try to create a group$/
     */
    public function tryGroupCreation()
    {
        try {
            $this->createPage->open();
            $this->createPage->createGroup(self::GROUP_NAME);
        } catch (UnexpectedPageException $e) {
        }
    }

    /**
     * @Then /^Group should be created$/
     */
    public function groupShouldBeCreated()
    {
        $group = $this->findGroup(self::GROUP_NAME);

        Assert::true($this->listPage->isOpen(), 'Group list page is not open');
        Assert::isInstanceOf($group, Group::class, 'Group not created!');
        Assert::true($this->listPage->isGroupCreationMessageShown(), 'Group message not shown');
    }

    /**
     * @Then /^I should not be allowed to create a group/
     */
    public function groupShouldNotBeAllowedToBeCreated()
    {
        Assert::eq($this->getSession()->getStatusCode(), 403, 'Group creation enabled!');
    }
}