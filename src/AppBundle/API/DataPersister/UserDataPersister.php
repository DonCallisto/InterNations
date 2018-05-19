<?php

declare(strict_types=1);

namespace AppBundle\API\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use AppBundle\API\DTO\User;
use AppBundle\CommandHandler\TokenAwareUserCommandHandler;
use AppBundle\CommandHandler\UserHandlingForbiddenException;
use Doctrine\ORM\EntityManagerInterface;

class UserDataPersister implements DataPersisterInterface
{
    private $entityManager;

    private $commandHandler;

    public function __construct(EntityManagerInterface $em, TokenAwareUserCommandHandler $commandHandler)
    {
        $this->entityManager = $em;
        $this->commandHandler = $commandHandler;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     *
     * @throws UserHandlingForbiddenException
     */
    public function persist($data)
    {
        $user = $this->commandHandler->register($data);
        $data->id = $user->getId();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}