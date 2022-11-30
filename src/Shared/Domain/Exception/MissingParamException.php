<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Exception;

use Exception;
use Throwable;

class MissingParamException extends Exception
{
    private const MESSAGE = 'Missing parameter - %s';

    public function __construct(string $argument, Throwable $previous = null)
    {
        $message = sprintf(self::MESSAGE, $argument);
        parent::__construct($message, 0, $previous);
    }
}
