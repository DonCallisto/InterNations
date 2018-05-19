<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use FOS\UserBundle\Model\User as FOSUser;
use Ramsey\Uuid\Uuid;

class User extends FOSUser
{
    protected $id;

    private $name;

    protected $groups;

    public function __construct(string $username, string $email, string $plainPassword, string $name)
    {
        parent::__construct();
        $this->id = Uuid::uuid4()->toString();
        $this->username = $username;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->name = $name;
        $this->groups = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $newName)
    {
        $this->name = $newName;
    }

    public function changeEmail(string $newEmail)
    {
        $this->setEmail($newEmail);
    }

    public function changePassword(string $newPassword)
    {
        $this->setPlainPassword($newPassword);
    }

    public function addGroupToUser(Group $group): bool
    {
        $criteria = $this->getGroupMatchingCriteriaForFilters($group);

        if ($this->groups->matching($criteria)->contains($group)) {
            return false;
        }

        $this->groups->add($group);
        $group->addUser($this);

        return true;
    }

    private function getGroupMatchingCriteriaForFilters(Group $group): Criteria
    {
        return Criteria::create()->where(Criteria::expr()->eq("id", $group->getId()));
    }

    public function removeGroupFromUser(Group $group): bool
    {
        $criteria = $this->getGroupMatchingCriteriaForFilters($group);

        if (!$this->groups->matching($criteria)->contains($group)) {
            return false;
        }

        $this->groups->removeElement($group);
        $group->removeUser($this);

        return true;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function joinGroups(?Collection $groups)
    {
        $this->groups = $groups;
    }
}