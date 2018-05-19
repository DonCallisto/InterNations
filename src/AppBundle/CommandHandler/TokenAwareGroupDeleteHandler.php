<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Model\Group\GroupDeleteCommand;
use AppBundle\Model\Group\GroupDeleteCommandInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TokenAwareGroupDeleteHandler
{
    private $authChecker;

    private $commandHandler;

    public function __construct(AuthorizationCheckerInterface $authChecker, GroupDeleteCommandHandler $commandHandler)
    {
        $this->authChecker = $authChecker;
        $this->commandHandler = $commandHandler;
    }

    /**
     * @throws GroupHandlingForbiddenException
     * @throws GroupNotFoundException
     */
    public function delete(GroupDeleteCommandInterface $command): bool
    {
        if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
            throw new GroupHandlingForbiddenException("Only admin user can handle group commands");
        }

        return $this->commandHandler->delete($command);
    }
}