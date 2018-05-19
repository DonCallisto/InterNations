<?php

declare(strict_types=1);

namespace InternationsBehat\Page\User;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class LoginPage extends Page
{
    protected $path = '/login';

    public function login(string $username, string $password)
    {
        // We should use data attribute instead of labels in order to make this less fragile
        $this->fillField('Username', $username);
        $this->fillField('Password', $password);
        $this->pressButton('Log in');
    }
}