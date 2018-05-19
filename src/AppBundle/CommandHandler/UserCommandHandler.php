<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Entity\User;
use AppBundle\Factory\UserFactory;
use AppBundle\Model\User\UserRegistrationCommandInterface;
use AppBundle\Model\User\UserUpdateCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserCommandHandler
{
    private $userFactory;

    private $em;

    private $validator;

    public function __construct(UserFactory $userFactory, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->userFactory = $userFactory;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function register(UserRegistrationCommandInterface $command): User
    {
        $errors = $this->validator->validate($command);
        if ($errors->count()) {
            // Here we should provide more verbose message about error. Not done because that's just and example
            throw new \InvalidArgumentException('Please, provide a valid command');
        }

        $user = $this->userFactory->create(
            $command->getUsername(),
            $command->getEmail(),
            $command->getPassword(),
            $command->getName()
        );

        $user->joinGroups($command->getGroups());
        $user->setEnabled(true);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function update(User $user, UserUpdateCommand $command): User
    {
        if ($command->email) {
            $user->changeEmail($command->email);
        }

        if ($command->password) {
            $user->changePassword($command->password);
        }

        if ($command->name) {
            $user->changeName($command->name);
        }

        $user->joinGroups($command->groups);

        $this->em->flush();

        return $user;
    }
}