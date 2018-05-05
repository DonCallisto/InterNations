<?php

declare(strict_types=1);

namespace InternationsBehat\Context\Ui\User;

use InternationsBehat\Context\Ui\BaseUiContext;
use InternationsBehat\Page\User\LoginPage;

class LoginContext extends BaseUiContext
{
    /**
     * @var LoginPage
     */
    private $loginPage;

    /**
     * @param LoginPage $loginPage
     */
    public function __construct(LoginPage $loginPage)
    {
        $this->loginPage = $loginPage;
    }

    /**
     * @Given /^I log in as "([^"]*)" with password "([^"]*)"$/
     */
    public function login(string $username, string $password)
    {
        $this->loginPage->open();
        $this->loginPage->login($username, $password);
    }
}