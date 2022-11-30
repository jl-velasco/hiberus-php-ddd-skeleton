<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Bus\Event\RabbitMq;

class RabbitMqQueueNameFormatter
{
    public static function format(string $subscriber): string
    {
        $subscriberClassPaths = explode('\\', $subscriber);

        $name = array_pop($subscriberClassPaths);

        return strtolower($name);
    }

    public static function formatQueueName(string $queueName): string
    {
        return strtolower(str_replace(['.', '_', 'version'], [''], $queueName));
    }

    public static function formatRetry(string $subscriber): string
    {
        $queueName = self::format($subscriber);

        return self::retry($queueName);
    }

    public static function formatDeadLetter(string $subscriber): string
    {
        $queueName = self::format($subscriber);

        return self::deadLetter($queueName);
    }

    public static function retry(string $queueName): string
    {
        return "retry.{$queueName}";
    }

    public static function deadLetter(string $queueName): string
    {
        return "dead_letter.{$queueName}";
    }

    public static function clean(string $queueName): string
    {
        foreach (['retry.', 'dead_letter.'] as $clean) {
            $queueName = str_starts_with($queueName, $clean) ? substr($queueName, \strlen($clean)) : $queueName;
        }

        return $queueName;
    }
}
