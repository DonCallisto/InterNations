<?php

declare(strict_types=1);

namespace Tests\PHPSpec\AppBundle\CommandHandler;

use AppBundle\CommandHandler\UserCommandHandler;
use AppBundle\CommandHandler\UserHandlingForbiddenException;
use AppBundle\Entity\User;
use AppBundle\Model\User\UserRegistrationCommand;
use AppBundle\Model\User\UserUpdateCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TokenAwareUserCommandHandlerSpec extends ObjectBehavior
{
    public function let(AuthorizationCheckerInterface $authChecker, UserCommandHandler $userCommandHandler)
    {
        $this->beConstructedWith($authChecker, $userCommandHandler);
    }

    public function it_does_not_let_non_admin_user_to_create_user(
        AuthorizationCheckerInterface $authChecker,
        UserCommandHandler $userCommandHandler,
        UserRegistrationCommand $command
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(false);

        $userCommandHandler->register(Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(UserHandlingForbiddenException::class)->duringRegister($command);
    }

    public function it_does_let_admin_user_to_create_user(
        AuthorizationCheckerInterface $authChecker,
        UserCommandHandler $userCommandHandler,
        UserRegistrationCommand $command,
        User $user
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(true);

        $userCommandHandler->register($command)->shouldBeCalled()->willReturn($user);

        $this->register($command)->shouldReturn($user);
    }

    public function it_does_not_let_non_admin_user_to_update_user(
        AuthorizationCheckerInterface $authChecker,
        UserCommandHandler $userCommandHandler,
        UserUpdateCommand $command,
        User $user
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(false);

        $userCommandHandler->register(Argument::any(), Argument::any())->shouldNotBeCalled();

        $this->shouldThrow(UserHandlingForbiddenException::class)->duringUpdate($user, $command);
    }

    public function it_does_let_admin_user_to_update_user(
        AuthorizationCheckerInterface $authChecker,
        UserCommandHandler $userCommandHandler,
        UserUpdateCommand $command,
        User $user
    ) {
        $authChecker->isGranted('ROLE_ADMIN')->willReturn(true);

        $userCommandHandler->update($user, $command)->shouldBeCalled()->willReturn($user);

        $this->update($user, $command)->shouldReturn($user);
    }
}
