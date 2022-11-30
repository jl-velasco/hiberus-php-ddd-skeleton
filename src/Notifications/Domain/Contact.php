<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Notifications\Domain;

use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Name;

class Contact
{
    public function __construct(private readonly Email $email, private readonly Name $name)
    {
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function name(): Name
    {
        return $this->name;
    }
}
