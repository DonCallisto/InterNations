<?php

declare(strict_types=1);

namespace InternationsBehat\Page\User;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class UpdatePage extends Page
{
    protected $path = '/user/update/{id}';

    public function changeEmail(?string $email)
    {
        $form = $this->getForm();
        $form->insertEmail($email);
    }

    public function save()
    {
        $this->pressButton('Update');
    }

    public function hasEmail(string $email)
    {
        return $this->getForm()->getEmail() == $email;
    }

    public function getForm(): FormElement
    {
        return $this->createElement(FormElement::class);
    }
}