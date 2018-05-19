<?php

declare(strict_types=1);

namespace AppBundle\API\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use AppBundle\API\DTO\Group;
use AppBundle\API\DTO\User as UserDTO;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;

class UserDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass == UserDTO::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $user = $this->userRepo->find($id);
        if (!$user instanceof User) {
            return null;
        }

        if ($operationName == 'delete') {
            return  $user;
        }

        $userDTO = new UserDTO();
        $userDTO->id = $id;
        $userDTO->username = $user->getUsername();
        $userDTO->email = $user->getEmail();
        $userDTO->name = $user->getName();

        $groupDTOs = [];
        foreach ($user->getGroups() as $group) {
            $groupDTO = new Group();
            $groupDTO->id = $group->getId();
            $groupDTO->name = $group->getName();

            $groupDTOs[] = $groupDTO;
        }
        $userDTO->setGroups($groupDTOs);

        return $userDTO;
    }
}