<?php

declare(strict_types=1);

namespace InternationsBehat\Context\Ui\User;

use InternationsBehat\Context\BaseContext;
use InternationsBehat\Page\User\ListPage;
use InternationsBehat\Page\User\UpdatePage;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\UnexpectedPageException;
use Webmozart\Assert\Assert;

class UpdateContext extends BaseContext
{
    private $listPage;

    private $updatePage;

    public function __construct(ListPage $listPage, UpdatePage $updatePage)
    {
        $this->listPage = $listPage;
        $this->updatePage = $updatePage;
    }

    /**
     * @When /^I try to update user with "([^"]*)" username changing email to "([^"]*)"$/
     */
    public function updateUser(string $username, string $email)
    {
        $this->listPage->open();

        try {
            $userUuid = $this->listPage->openUpdatePage($username);
            $this->updatePage->open(['id' => $userUuid]);
            $this->updatePage->changeEmail($email);
            $this->updatePage->save();
        } catch (UnexpectedPageException $e) {

        }
    }

    /**
     * @When /^User with "([^"]*)" username email should be updated with "([^"]*)"$/
     */
    public function userShouldBeUpdated(string $username, string $email)
    {
        $this->listPage->open();
        $userUuid = $this->listPage->openUpdatePage($username);
        $this->updatePage->open(['id' => $userUuid]);
        Assert::true($this->updatePage->hasEmail($email), 'Email not changed!');
    }

    /**
     * @Then /^I should not be allowed to update user$/
     */
    public function userShouldNotBeAllowedToBeCreated()
    {
        Assert::eq($this->getSession()->getStatusCode(), 403, 'User update enabled!');
    }
}