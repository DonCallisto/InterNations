<?php

declare(strict_types=1);

namespace InternationsBehat\Page\Group;

use SensioLabs\Behat\PageObjectExtension\PageObject\Element;

class FormElement extends Element
{
    const EMAIL_LABEL = 'Email';

    protected $selector = 'form'; // should be a less generic one but for this example it's ok

    public function insertName(string $name)
    {
        $this->fillField('Name', $name);
    }
}