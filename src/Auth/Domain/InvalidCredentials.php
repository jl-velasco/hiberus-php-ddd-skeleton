<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Auth\Domain;

use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use RuntimeException;

final class InvalidCredentials extends RuntimeException
{
    public function __construct(Email $email)
    {
        parent::__construct(sprintf('The credentials for <%s> are invalid', $email->value()));
    }
}
