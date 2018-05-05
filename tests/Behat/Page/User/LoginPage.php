<?php

declare(strict_types=1);

namespace InternationsBehat\Page\User;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class LoginPage extends Page
{
    protected $path = '/login';

    public function login(string $username, string $password)
    {
        // @todo: change with data attribute after template override
        $this->fillField('Username', $username);
        $this->fillField('Password', $password);
        $this->pressButton('Log in');
    }
}