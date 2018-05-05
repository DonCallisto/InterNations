<?php

declare(strict_types=1);

namespace InternationsBehat\Page\Group;

use AppBundle\Controller\GroupController;
use AppBundle\Entity\Group;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class ListPage extends Page
{
    protected $path = '/group/list';

    public function isGroupCreationMessageShown(): bool
    {
        return $this->hasContent(GroupController::GROUP_CREATED_MSG);
    }

    public function deleteGroup(string $name)
    {
        $this->find('xpath', sprintf('//a[@data-group-delete="%s"]', $name))->click();
    }

    public function isGroupDeleteMessageShown(): bool
    {
        return $this->hasContent(GroupController::GROUP_DELETED_MSG);
    }

    public function isGroupCannoteDeleteDueToUsersMessageShown(): bool
    {
        return $this->hasContent(GroupController::GROUP_WITH_USERS_MSG);
    }
}