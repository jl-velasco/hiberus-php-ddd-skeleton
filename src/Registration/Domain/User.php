<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Registration\Domain;

use Hiberus\Skeleton\Registration\Domain\Events\UserCreatedDomainEvent;
use Hiberus\Skeleton\Registration\Domain\Events\UserUpdatedEmailDomainEvent;
use Hiberus\Skeleton\Registration\Domain\Events\UserUpdatedNameDomainEvent;
use Hiberus\Skeleton\Registration\Domain\Events\UserUpdatedPasswordDomainEvent;
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
        private Name $name,
        private Email $email,
        private Password $password,
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

    /** @throws InvalidValueException */
    public function updateEmail(Email $newEmail): void
    {
        if (!$this->email()->isEquals($newEmail)) {
            $this->email = $newEmail;
            $this->record(
                new UserUpdatedEmailDomainEvent(
                    $this->id()->value(),
                    $this->name()->value(),
                )
            );
        }
    }

    /** @throws InvalidValueException */
    public function updatePassword(Password $newPassword): void
    {
        if(!$this->password()->isEquals($newPassword)){
            $this->password = $newPassword;
            $this->record(
                new UserUpdatedPasswordDomainEvent(
                    $this->id()->value(),
                    $this->email()->value(),
                    $this->password()->value(),
                )
            );
        }
    }

    /** @throws InvalidValueException */
    public function updateName(Name $newName): void
    {
        if(!$this->name()->isEquals($newName)){
            $this->name = $newName;
            $this->record(
                new UserUpdatedNameDomainEvent(
                    $this->id()->value(),
                    $this->name()->value()
                )
            );
        }
    }

    /**
     * @param array<string, mixed> $user
     * @throws InvalidValueException
     */
    public static function fromArray(array $user): self
    {
        return new self(
            new Uuid($user['id']),
            new Name($user['name']),
            new Email($user['email']),
            new Password($user['password'])
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id' => $this->id()->value(),
            'name' => $this->name()->value(),
            'email' => $this->email()->value(),
            'password' => $this->password()->value(),
        ];
    }
}
