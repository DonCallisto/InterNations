<?php

declare(strict_types=1);

namespace AppBundle\CommandHandler;

use Throwable;

class DeleteNonEmptyGroupException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        if (!$message) {
            $message = "An empty group can not be deleted";
        }

        parent::__construct($message, $code, $previous);
    }
}