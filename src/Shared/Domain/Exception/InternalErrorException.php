<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Exception;

use Throwable;

class InternalErrorException extends \Exception
{
    private const MESSAGE = 'Internal error exception';

    public function __construct(string $message = null, Throwable $previous = null)
    {
        parent::__construct($message ?? self::MESSAGE, 0, $previous);
    }
}
