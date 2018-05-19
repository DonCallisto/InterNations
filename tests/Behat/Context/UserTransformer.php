<?php

declare(strict_types=1);

namespace InternationsBehat\Context;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;

trait UserTransformer
{
    /**
     * @Transform /^(?:U|u)ser with "([^"]*)" username$/
     */
    public function fromUsernameToUser(string $username): ?User
    {
        return $this->getUserRepo()->findOneBy(['username' => $username]);
    }

    protected abstract function getUserRepo(): UserRepository;
}