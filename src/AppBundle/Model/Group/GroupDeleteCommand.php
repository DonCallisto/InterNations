<?php

declare(strict_types=1);

namespace AppBundle\Model\Group;

class GroupDeleteCommand implements GroupDeleteCommandInterface
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}