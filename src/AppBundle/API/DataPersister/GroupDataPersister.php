<?php

declare(strict_types=1);

namespace AppBundle\API\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use AppBundle\API\DTO\Group;
use AppBundle\CommandHandler\DeleteNonEmptyGroupException;
use AppBundle\CommandHandler\GroupHandlingForbiddenException;
use AppBundle\CommandHandler\GroupNotFoundException;
use AppBundle\CommandHandler\TokenAwareGroupDeleteHandler;
use AppBundle\CommandHandler\TokenAwareGroupPersisterHandler;

class GroupDataPersister implements DataPersisterInterface
{
    private $persisterHandler;

    private $deleteHandler;

    public function __construct(
        TokenAwareGroupPersisterHandler $persisterHandler,
        TokenAwareGroupDeleteHandler $deleteHandler
    ) {
        $this->persisterHandler = $persisterHandler;
        $this->deleteHandler = $deleteHandler;
    }

    public function supports($data): bool
    {
        return $data instanceof Group;
    }

    /**
     * @param Group
     *
     * @throws GroupHandlingForbiddenException
     */
    public function persist($data)
    {
        $group = $this->persisterHandler->create($data);
        $data->id = $group->getId();
    }

    /**
     * @throws GroupHandlingForbiddenException
     * @throws GroupNotFoundException
     * @throws DeleteNonEmptyGroupException
     */
    public function remove($data)
    {
        $result = $this->deleteHandler->delete($data);
        if (!$result) {
            throw new DeleteNonEmptyGroupException();
        }
    }
}