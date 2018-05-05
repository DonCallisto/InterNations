<?php

declare(strict_types=1);

namespace InternationsBehat\Page\User;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class FormElement extends Element
{
    const EMAIL_LABEL = 'Email';

    protected $selector = 'form'; // should be a less generic one but for this example it's ok

    public function insertUsername(string $username)
    {
        // We should use data attribute in order to have more robust test but as that's only an example...
        // Valid also for other fields
        $this->fillField('Username', $username);
    }

    public function insertEmail(string $email)
    {
        $this->fillField(self::EMAIL_LABEL, $email);
    }

    public function insertPassword(string $password)
    {
        $this->fillField('Password', $password);
    }

    public function insertName(string $name)
    {
        $this->fillField('Name', $name);
    }

    public function getEmail(): ?string
    {
        return $this->findField(self::EMAIL_LABEL)->getValue();
    }
}