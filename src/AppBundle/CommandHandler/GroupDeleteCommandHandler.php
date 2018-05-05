<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use AppBundle\Entity\Group;
use AppBundle\Model\Group\GroupDeleteCommand;
use AppBundle\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupDeleteCommandHandler
{
    private $em;

    private $repo;

    public function __construct(EntityManagerInterface $em, GroupRepository $repo)
    {
        $this->em = $em;
        $this->repo = $repo;
    }

    /**
     * @throws GroupNotFoundException
     */
    public function delete(GroupDeleteCommand $command)
    {
        /** @var $group Group|null */
        $group = $this->repo->find($command->getId());
        if (!$group instanceof Group) {
            throw new GroupNotFoundException(sprintf('Group %s not found', $command->getId()));
        }

        if ($group->hasUsers()) {
            return false;
        }

        $this->em->remove($group);
        $this->em->flush();

        return true;
    }
}