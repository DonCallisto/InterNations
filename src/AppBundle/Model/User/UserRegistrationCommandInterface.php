<?php

declare(strict_types=1);

namespace AppBundle\Model\User;

use Doctrine\Common\Collections\Collection;

interface UserRegistrationCommandInterface
{
    public function getUsername(): ?string;

    public function getEmail(): ?string;

    public function getPassword(): ?string;

    public function getName(): ?string;

    public function getGroups(): ?Collection;
}