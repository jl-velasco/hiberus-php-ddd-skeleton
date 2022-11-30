<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

class Date
{
    private const TIMEZONE = 'UTC';

    private const DATABASE_TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    private DateTimeImmutable $date;

    /** @throws InvalidValueException */
    public function __construct(?string $date = null)
    {
        try {
            $this->date = $date ?
                new DateTimeImmutable($date) :
                new DateTimeImmutable('now', new DateTimeZone(self::TIMEZONE));
        } catch (Exception) {
            throw new InvalidValueException("Invalid date value {$date}");
        }
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function stringDateTime(): string
    {
        return $this->date
            ->format(self::DATABASE_TIMESTAMP_FORMAT);
    }

    public function modify(string $modifier): self
    {
        $this->date = (new DateTimeImmutable())->modify($modifier);

        return $this;
    }

    public function isInThePast(): bool
    {
        return $this->date < new DateTimeImmutable();
    }

    public function year(): int
    {
        return (int) $this->date->format('Y');
    }

    public function month(): int
    {
        return (int) $this->date->format('m');
    }

    public function toFormat(string $format): string
    {
        return $this->date->format($format);
    }

    public function isGreatherThan(Date $date): bool
    {
        $dateInterval = $this->date->diff($date->date());

        return !($dateInterval->format('%R%a') >= 0);
    }

    public function __toString(): string
    {
        return $this->stringDateTime();
    }
}
