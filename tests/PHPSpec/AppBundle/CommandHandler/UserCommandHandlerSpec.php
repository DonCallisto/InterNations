<?php

declare(strict_types=1);

namespace Tests\PHPSpec\AppBundle\CommandHandler;

use AppBundle\Entity\User;
use AppBundle\Factory\UserFactory;
use AppBundle\Model\User\UserRegistrationCommand;
use AppBundle\Model\User\UserUpdateCommand;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserCommandHandlerSpec extends ObjectBehavior
{
    public function let(UserFactory $userFactory, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->beConstructedWith($userFactory, $em, $validator);
    }

    public function it_should_register_a_user(
        UserFactory $userFactory,
        User $user,
        EntityManagerInterface $em,
        UserRegistrationCommand $urc,
        ValidatorInterface $validator
    ) {
        $username = 'username';
        $email = 'email';
        $plainPwd = 'plainPwd';
        $name = 'name';

        $urc->username = $username;
        $urc->email = $email;
        $urc->password = $plainPwd;
        $urc->name = $name;

        $validator->validate($urc)->willReturn(new ConstraintViolationList([]));

        $userFactory->create($username, $email, $plainPwd, $name)->willReturn($user);
        $em->persist($user)->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->register($urc)->shouldReturn($user);
    }

    public function it_should_throw_exception_if_registration_command_is_not_valid(
        UserRegistrationCommand $urc,
        ValidatorInterface $validator
    ) {
        $violations = new ConstraintViolationList([
            new ConstraintViolation('message', 'template', [], 'root', 'pp', 'iv')
        ]);
        $validator->validate($urc)->willReturn($violations);

        $this->shouldThrow(\InvalidArgumentException::class)->duringRegister($urc);
    }

    public function it_should_update_a_user(
        User $user,
        UserUpdateCommand $uuc,
        EntityManagerInterface $em
    ) {
        $email = 'email';
        $password = 'pwd';
        $name = 'name';

        $uuc->email = $email;
        $uuc->password = $password;
        $uuc->name = $name;

        $user->changeName($name)->shouldBeCalled();
        $user->changePassword($password)->shouldBeCalled();
        $user->changeEmail($email)->shouldBeCalled();

        $em->flush()->shouldBeCalled();

        $this->update($user, $uuc)->shouldReturn($user);
    }

    public function it_should_not_update_email_if_no_value_provided(
        User $user,
        UserUpdateCommand $uuc,
        EntityManagerInterface $em
    ) {
        $email = '';
        $password = 'pwd';
        $name = 'name';

        $uuc->email = $email;
        $uuc->password = $password;
        $uuc->name = $name;

        $user->changeName($name)->shouldBeCalled();
        $user->changePassword($password)->shouldBeCalled();
        $user->changeEmail(Argument::any())->shouldNotBeCalled();

        $em->flush()->shouldBeCalled();

        $this->update($user, $uuc)->shouldReturn($user);
    }

    public function it_should_not_update_password_if_no_value_provided(
        User $user,
        UserUpdateCommand $uuc,
        EntityManagerInterface $em
    ) {
        $email = 'email';
        $password = '';
        $name = 'name';

        $uuc->email = $email;
        $uuc->password = $password;
        $uuc->name = $name;

        $user->changeName($name)->shouldBeCalled();
        $user->changePassword(Argument::any())->shouldNotBeCalled();
        $user->changeEmail($email)->shouldBeCalled();

        $em->flush()->shouldBeCalled();

        $this->update($user, $uuc)->shouldReturn($user);
    }

    public function it_should_not_update_name_if_no_value_provided(
        User $user,
        UserUpdateCommand $uuc,
        EntityManagerInterface $em
    ) {
        $email = 'email';
        $password = 'pwd';
        $name = '';

        $uuc->email = $email;
        $uuc->password = $password;
        $uuc->name = $name;

        $user->changeName($name)->shouldNotBeCalled();
        $user->changePassword($password)->shouldBeCalled();
        $user->changeEmail($email)->shouldBeCalled();

        $em->flush()->shouldBeCalled();

        $this->update($user, $uuc)->shouldReturn($user);
    }
}