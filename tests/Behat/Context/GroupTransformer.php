<?php

declare(strict_types=1);

namespace InternationsBehat\Context;

use AppBundle\Entity\Group;
use AppBundle\Repository\GroupRepository;

trait GroupTransformer
{
    /**
     * @Transform /^(?:G|g)roup with "([^"]*)" name/
     */
    public function fromNameToGroup(string $name): ?Group
    {
        return $this->getGroupRepo()->findOneBy(['name' => $name]);
    }

    protected abstract function getGroupRepo(): GroupRepository;
}