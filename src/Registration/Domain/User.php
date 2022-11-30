<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Domain;

use Hiberus\Skeleton\Shared\Domain\Aggregate\AggregateRoot;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Name;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Password;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;

class User extends AggregateRoot
{
    public function __construct(
        private readonly Uuid $id,
        private readonly Name $name,
        private readonly Email $email,
        private readonly Password $password,
    ) {
    }

    /** @throws InvalidValueException */
    public static function create(
        Uuid $id,
        Name $name,
        Email $email,
        Password $password,
    ): User {
        $user = new self($id, $name, $email, $password);
        $user->record(
            new UserCreatedDomainEvent(
                $id->value(),
                $name->value(),
                $email->value()
            )
        );

        return $user;
    }

    /** @throws InvalidValueException */
    public static function fromPrimitives(
        string $id,
        string $name,
        string $email,
        string $password
    ): self {
        return new self(
            new Uuid($id),
            new Name($name),
            new Email($email),
            new Password($password)
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
