<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Entity\Group;
use AppBundle\Model\Group\GroupCreationCommandInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TokenAwareGroupPersisterHandler
{
    private $authChecker;

    private $groupCommandHandler;

    public function __construct(AuthorizationCheckerInterface $authChecker, GroupPersisterCommandHandler $groupCommandHandler)
    {
        $this->authChecker = $authChecker;
        $this->groupCommandHandler = $groupCommandHandler;
    }

    /**
     * @throws GroupHandlingForbiddenException
     */
    public function create(GroupCreationCommandInterface $command): Group
    {
        $this->throwExceptionIfUserNotAdmin();

        return $this->groupCommandHandler->create($command);
    }

    /**
     * @throws GroupHandlingForbiddenException
     */
    private function throwExceptionIfUserNotAdmin()
    {
        if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
            throw new GroupHandlingForbiddenException("Only admin user can handle group commands");
        }
    }
}
