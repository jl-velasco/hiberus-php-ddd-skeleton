<?php
declare(strict_types = 1);

namespace Hiberus\Skeleton\Ticket\Domain;

enum Status: int
{
    case CLOSE = 0;
    case OPEN = 1;
    case IN_PROGRESS = 2;
    case RESOLVED = 3;
    case REOPEN = 4;

    public static function toString(self $value): string
    {
        return match ($value) {
            self::CLOSE => 'close',
            self::OPEN => 'open',
            self::IN_PROGRESS => 'in_progress',
            self::RESOLVED => 'resolved',
            self::REOPEN => 'reopen',
        };
    }

    public static function fromString(string $value): self
    {
        return match ($value) {
            'close' => self::CLOSE,
            'open' => self::OPEN,
            'in_progress' => self::IN_PROGRESS,
            'resolved' => self::RESOLVED,
            'reopen' => self::REOPEN,
            default => throw new \InvalidArgumentException('Invalid status'),
        };
    }

    public function equals(Status $status): bool
    {
        return $this->value === $status->value;
    }
}
