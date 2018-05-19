<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Entity\Group;
use AppBundle\Factory\GroupFactory;
use AppBundle\Model\Group\GroupCreationCommandInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GroupPersisterCommandHandler
{
    private $groupFactory;

    private $em;

    private $validator;

    public function __construct(GroupFactory $groupFactory, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->groupFactory = $groupFactory;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function create(GroupCreationCommandInterface $command): Group
    {
        $errors = $this->validator->validate($command);
        if ($errors->count()) {
            // Here we should provide more verbose message about error. Not done because that's just and example
            throw new \InvalidArgumentException('Please, provide a valid command');
        }

        $group = $this->groupFactory->create($command->getName());

        $this->em->persist($group);
        $this->em->flush();

        return $group;
    }
}