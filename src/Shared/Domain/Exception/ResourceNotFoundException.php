<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\Exception;

use Exception;

class ResourceNotFoundException extends Exception
{
    private const MESSAGE_TEMPLATE = '%s identified by %s not found';

    public function __construct(string $objectType, string $searchValue)
    {
        $message = sprintf(self::MESSAGE_TEMPLATE, $objectType, $searchValue);

        parent::__construct($message);
    }
}
