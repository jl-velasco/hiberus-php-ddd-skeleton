<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\RabbitMq;

final class RabbitMqExchangeNameFormatter
{
    public static function retry(string $exchangeName): string
    {
        return "retry_{$exchangeName}";
    }

    public static function deadLetter(string $exchangeName): string
    {
        return "dead_letter_{$exchangeName}";
    }
}
