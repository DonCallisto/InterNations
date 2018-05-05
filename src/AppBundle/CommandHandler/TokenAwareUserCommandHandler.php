<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Entity\User;
use AppBundle\Model\User\UserRegistrationCommand;
use AppBundle\Model\User\UserUpdateCommand;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TokenAwareUserCommandHandler
{
    private $authChecker;

    private $userCommandHandler;

    public function __construct(AuthorizationCheckerInterface $authChecker, UserCommandHandler $userCommandHandler)
    {
        $this->authChecker = $authChecker;
        $this->userCommandHandler = $userCommandHandler;
    }

    /**
     * @throws UserHandlingForbiddenException
     */
    public function register(UserRegistrationCommand $command): User
    {
        $this->throwExceptionIfUserNotAdmin();

        return $this->userCommandHandler->register($command);
    }

    /**
     * @throws UserHandlingForbiddenException
     */
    private function throwExceptionIfUserNotAdmin()
    {
        if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
            throw new UserHandlingForbiddenException("Only admin user can handle user commands");
        }
    }

    /**
     * @throws UserHandlingForbiddenException
     */
    public function update(User $user, UserUpdateCommand $command): User
    {
        $this->throwExceptionIfUserNotAdmin();

        return $this->userCommandHandler->update($user, $command);
    }
}
