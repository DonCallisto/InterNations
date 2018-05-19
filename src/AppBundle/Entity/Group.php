<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Ramsey\Uuid\Uuid;

class Group
{
    private $id;

    private $name;

    private $users;

    public function __construct(string $name)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->users = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasUsers(): bool
    {
        return $this->users->count() != 0;
    }

    public function addUser(User $user): bool
    {
        $criteria = $this->getUserMatchingCriteriaForFilters($user);

        if ($this->users->matching($criteria)->contains($user)) {
            return false;
        }

        $this->users->add($user);
        $user->addGroupToUser($this);

        return true;
    }

    public function getUsers()
    {
        return $this->users;
    }

    private function getUserMatchingCriteriaForFilters(User $user): Criteria
    {
        return Criteria::create()->where(Criteria::expr()->eq("id", $user->getId()));
    }

    public function removeUser(User $user): bool
    {
        $criteria = $this->getUserMatchingCriteriaForFilters($user);

        if (!$this->users->matching($criteria)->contains($user)) {
            return false;
        }

        $this->users->removeElement($user);
        $user->removeGroupFromUser($this);

        return true;
    }
}