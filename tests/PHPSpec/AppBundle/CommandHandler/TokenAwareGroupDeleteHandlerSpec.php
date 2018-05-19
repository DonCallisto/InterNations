<?php

declare(strict_types=1);

namespace Tests\PHPSpec\AppBundle\CommandHandler;

use AppBundle\CommandHandler\GroupDeleteCommandHandler;
use AppBundle\CommandHandler\GroupHandlingForbiddenException;
use AppBundle\CommandHandler\GroupNotFoundException;
use AppBundle\Model\Group\GroupDeleteCommandInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TokenAwareGroupDeleteHandlerSpec extends ObjectBehavior
{
    public function let(AuthorizationCheckerInterface $authChecker, GroupDeleteCommandHandler $commandHandler)
    {
        $this->beConstructedWith($authChecker, $commandHandler);
    }

    public function it_does_not_let_non_admin_user_to_delete_group(
        AuthorizationCheckerInterface $authChecker,
        GroupDeleteCommandHandler $commandHandler,
        GroupDeleteCommandInterface $command
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(false);

        $commandHandler->delete(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(GroupHandlingForbiddenException::class)->duringDelete($command);
    }

    public function it_does_let_admin_user_to_create_user(
        AuthorizationCheckerInterface $authChecker,
        GroupDeleteCommandHandler $commandHandler,
        GroupDeleteCommandInterface $command
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(true);

        $commandHandler->delete($command)->shouldBeCalled()->willReturn(true);

        $this->delete($command)->shouldReturn(true);
    }

    public function it_cause_an_error_if_group_does_not_exists(
        AuthorizationCheckerInterface $authChecker,
        GroupDeleteCommandHandler $commandHandler,
        GroupDeleteCommandInterface $command
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(true);

        $commandHandler->delete($command)->willThrow(GroupNotFoundException::class);

        $this->shouldThrow(GroupNotFoundException::class)->duringDelete($command);
    }
}