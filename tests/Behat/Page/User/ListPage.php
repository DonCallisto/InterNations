<?php

declare(strict_types=1);

namespace InternationsBehat\Page\User;

use AppBundle\Controller\UserController;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class ListPage extends Page
{
    protected $path = '/user/list';

    public function isUserCreationMessageShown(): bool
    {
        return $this->hasContent(UserController::USER_CREATED_MSG);
    }

    public function openUpdatePage(string $username): string
    {
        $element = $this->find('xpath', sprintf('//a[@data-user-update="%s"]', $username));
        $uuid = substr($element->getAttribute('href'), -36);
        $element->click();

        return $uuid;
    }

    public function deleteUser(string $username)
    {
        $this->find('xpath', sprintf('//a[@data-user-delete="%s"]', $username))->click();
    }

    public function isUserDeleteMessageShown(): bool
    {
        return $this->hasContent(UserController::USER_DELETED_MSG);
    }

    public function isUserDeleteHimselfMessageShown(): bool
    {
        return $this->hasContent(UserController::USER_DELETE_HIMSELF_ERROR_MSG);
    }
}