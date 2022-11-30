<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Exception;

use Exception;

class AlreadyStoredException extends Exception
{
    private const MESSAGE = 'Item already stored identified';

    public function __construct(string $message = null, \Throwable $previous = null)
    {
        parent::__construct($message ?? self::MESSAGE, 0, $previous);
    }
}
