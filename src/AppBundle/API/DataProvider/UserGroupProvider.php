<?php

declare(strict_types=1);

namespace AppBundle\API\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use AppBundle\API\DTO\UserGroupDTO;
use AppBundle\Entity\Group;
use AppBundle\Repository\GroupRepository;

class UserGroupProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $groupRepo;

    public function __construct(GroupRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass == UserGroupDTO::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        $group = $this->groupRepo->find($id);
        if (!$group instanceof Group) {
            return null;
        }

        $dto = new UserGroupDTO();
        $dto->setGroup($group);

        return $dto;
    }
}