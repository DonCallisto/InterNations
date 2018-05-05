<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as FOSUser;

class User extends FOSUser
{
    protected $id;

    protected $name;

    public function __construct(string $username, string $email, string $plainPassword, string $name)
    {
        parent::__construct();
        $this->username = $username;
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $newName): self
    {
        $this->name = $newName;

        return $this;
    }

    public function changeEmail(string $newEmail): self
    {
        $this->setEmail($newEmail);

        return $this;
    }

    public function changePassword(string $newPassword): self
    {
        $this->setPlainPassword($newPassword);

        return $this;
    }
}