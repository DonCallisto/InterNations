<?php

declare(strict_types=1);

namespace AppBundle\API\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use AppBundle\API\DTO\Group as GroupDTO;
use AppBundle\API\DTO\User;
use AppBundle\Entity\Group;
use AppBundle\Repository\GroupRepository;

class GroupDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $groupRepo;

    public function __construct(GroupRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass == GroupDTO::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $group = $this->groupRepo->find($id);
        if (!$group instanceof Group) {
            return null;
        }

        $groupDTO = new GroupDTO();
        $groupDTO->id = $id;
        $groupDTO->name = $group->getName();

        $userDTOs = [];
        foreach ($group->getUsers() as $user) {
            $userDTO = new User();
            $userDTO->id = $user->getId();
            $userDTO->username = $user->getUsername();
            $userDTO->email = $user->getEmail();
            $userDTO->name = $user->getName();

            $userDTOs[] = $userDTO;
        }
        $groupDTO->setUsers($userDTOs);

        return $groupDTO;
    }
}