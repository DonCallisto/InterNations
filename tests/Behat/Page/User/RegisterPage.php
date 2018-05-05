<?php

declare(strict_types=1);

namespace InternationsBehat\Page\User;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class RegisterPage extends Page
{
    protected $path = '/user/register';

    public function registerUser(?string $username, ?string $email, ?string $password, ?string $name)
    {
        /** @var $form Element|FormElement */
        $form = $this->createElement(FormElement::class);
        $form->insertUsername($username);
        $form->insertEmail($email);
        $form->insertPassword($password);
        $form->insertName($name);
        $this->pressButton('Register');
    }
}