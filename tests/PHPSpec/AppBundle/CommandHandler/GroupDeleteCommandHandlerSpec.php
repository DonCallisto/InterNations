<?php

declare(strict_types=1);

namespace Tests\PHPSpec\AppBundle\CommandHandler;

use AppBundle\CommandHandler\GroupNotFoundException;
use AppBundle\Entity\Group;
use AppBundle\Model\Group\GroupDeleteCommand;
use AppBundle\Model\Group\GroupDeleteCommandInterface;
use AppBundle\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;

class GroupDeleteCommandHandlerSpec extends ObjectBehavior
{
    public function let(EntityManagerInterface $em, GroupRepository $repo)
    {
        $this->beConstructedWith($em, $repo);
    }

    public function it_delete_group_if_not_contains_any_user(
        EntityManagerInterface $em,
        GroupRepository $repo,
        GroupDeleteCommandInterface $command,
        Group $group
    ) {
        $id = 'id';
        $command->getId()->willReturn($id);

        $repo->find($id)->willReturn($group);

        $group->hasUsers()->willReturn(false);

        $em->remove($group)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->delete($command)->shouldReturn(true);
    }

    public function it_wont_delete_group_if_group_contains_at_least_a_user(
        EntityManagerInterface $em,
        GroupRepository $repo,
        GroupDeleteCommandInterface $command,
        Group $group
    ) {
        $id = 'id';
        $command->getId()->willReturn($id);

        $repo->find($id)->willReturn($group);

        $group->hasUsers()->willReturn(true);

        $em->remove($group)->shouldNotBeCalled();
        $em->flush()->shouldNotBeCalled();

        $this->delete($command)->shouldReturn(false);
    }

    public function it_cause_an_error_if_group_does_not_exists(
        GroupRepository $repo,
        GroupDeleteCommandInterface $command
    ) {
        $id = 'id';
        $command->getId()->willReturn($id);

        $repo->find($id)->willReturn(null);

        $this->shouldThrow(GroupNotFoundException::class)->duringDelete($command);
    }
}