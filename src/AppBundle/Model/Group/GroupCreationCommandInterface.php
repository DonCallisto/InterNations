<?php

declare(strict_types=1);

namespace AppBundle\Model\Group;

interface GroupCreationCommandInterface
{
    public function getName(): ?string;
}