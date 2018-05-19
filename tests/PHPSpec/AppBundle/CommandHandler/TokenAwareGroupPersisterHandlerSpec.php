<?php

declare(strict_types=1);

namespace Tests\PHPSpec\AppBundle\CommandHandler;

use AppBundle\CommandHandler\GroupHandlingForbiddenException;
use AppBundle\CommandHandler\GroupPersisterCommandHandler;
use AppBundle\Entity\Group;
use AppBundle\Model\Group\GroupCreationCommandInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TokenAwareGroupPersisterHandlerSpec extends ObjectBehavior
{
    public function let(AuthorizationCheckerInterface $authChecker, GroupPersisterCommandHandler $commandHandler)
    {
        $this->beConstructedWith($authChecker, $commandHandler);
    }

    public function it_does_not_let_non_admin_user_to_create_a_group(
        AuthorizationCheckerInterface $authChecker,
        GroupPersisterCommandHandler $commandHandler,
        GroupCreationCommandInterface $command
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(false);

        $commandHandler->create(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(GroupHandlingForbiddenException::class)->duringCreate($command);
    }

    public function it_does_let_admin_user_to_create_group(
        AuthorizationCheckerInterface $authChecker,
        GroupPersisterCommandHandler $commandHandler,
        GroupCreationCommandInterface $command,
        Group $group
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(true);

        $commandHandler->create($command)->shouldBeCalled()->willReturn($group);

        $this->create($command)->shouldReturn($group);
    }
}
