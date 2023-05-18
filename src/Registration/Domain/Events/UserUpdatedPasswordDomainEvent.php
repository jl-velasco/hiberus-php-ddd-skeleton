<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Domain\Events;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

final class UserUpdatedPasswordDomainEvent extends DomainEvent
{
    public function __construct(
        string $id,
        private readonly string $email,
        private readonly string $password,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'hiberus.hiberus_skeleton.event.user_password.updated';
    }

    /**
     * @param array<mixed> $body
     *
     * @throws InvalidValueException
     */
    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): UserUpdatedPasswordDomainEvent {
        return new self(
            $aggregateId,
            $body['email'],
            $body['password'],
            $eventId,
            $occurredOn
        );
    }

    /** @return array<string, string> */
    public function toPrimitives(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
