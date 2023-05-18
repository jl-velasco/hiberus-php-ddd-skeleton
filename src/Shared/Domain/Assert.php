<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain;

use InvalidArgumentException;

final class Assert
{
    /** @param array<int, string> $items */
    public static function arrayOf(string $class, array $items): void
    {
        foreach ($items as $item) {
            self::instanceOf($class, $item);
        }
    }

    public static function instanceOf(string $class, mixed $item): void
    {
        if (!$item instanceof $class) {
            throw new InvalidArgumentException(
                sprintf('The object <%s> is not an instance of <%s>', $class, \get_class($item))
            );
        }
    }

    public static function subclassOf(string $value, string $className, string $message = null): bool
    {
        if (!\is_subclass_of($value, $className)) {
            throw new InvalidArgumentException(
                $message ?: sprintf('Class <%s> was expected to be subclass of <%s>.', \get_class((object) $value), $className)
            );
        }

        return true;
    }
}
