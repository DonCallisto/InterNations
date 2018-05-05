<?php

declare(strict_types=1);

namespace AppBundle\Model\Group;

use Symfony\Component\Validator\Constraints as Assert;

class GroupCreationCommand
{
    /**
     * @Assert\NotBlank()
     */
    public $name;
}