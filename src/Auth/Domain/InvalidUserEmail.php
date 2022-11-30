<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Domain;

use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use RuntimeException;

final class InvalidUserEmail extends RuntimeException
{
    public function __construct(Email $email)
    {
        parent::__construct(sprintf('The email <%s> does not exists', $email->value()));
    }
}
