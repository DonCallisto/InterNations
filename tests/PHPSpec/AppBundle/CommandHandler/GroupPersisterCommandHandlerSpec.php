<?php

declare(strict_types=1);

namespace Tests\PHPSpec\AppBundle\CommandHandler;

use AppBundle\Entity\Group;
use AppBundle\Factory\GroupFactory;
use AppBundle\Model\Group\GroupCreationCommand;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupPersisterCommandHandlerSpec extends ObjectBehavior
{
    public function let(GroupFactory $groupFactory, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->beConstructedWith($groupFactory, $em, $validator);
    }

    public function it_should_create_a_group(
        GroupFactory $groupFactory,
        Group $group,
        EntityManagerInterface $em,
        GroupCreationCommand $gcc,
        ValidatorInterface $validator
    )
    {
        $name = 'name';

        $gcc->name = $name;

        $validator->validate($gcc)->willReturn(new ConstraintViolationList([]));

        $groupFactory->create($name)->willReturn($group);
        $em->persist($group)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->create($gcc)->shouldReturn($group);
    }

    public function it_should_throw_exception_if_create_command_is_not_valid(
        GroupCreationCommand $gcc,
        ValidatorInterface $validator
    ) {
        $violations = new ConstraintViolationList([
            new ConstraintViolation('message', 'template', [], 'root', 'pp', 'iv')
        ]);
        $validator->validate($gcc)->willReturn($violations);

        $this->shouldThrow(\InvalidArgumentException::class)->duringCreate($gcc);
    }
}