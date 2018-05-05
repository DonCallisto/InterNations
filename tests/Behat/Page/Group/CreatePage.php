<?php

declare(strict_types=1);

namespace InternationsBehat\Page\Group;

use Behat\Mink\Element\Element;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class CreatePage extends Page
{
    protected $path = '/group/create';

    public function createGroup(?string $name)
    {
        /** @var $form Element|FormElement */
        $form = $this->createElement(FormElement::class);
        $form->insertName($name);
        $this->pressButton('Create');
    }
}