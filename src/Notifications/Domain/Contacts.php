<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Notifications\Domain;

use Hiberus\Skeleton\Shared\Domain\Collection;

/** @extends  Collection<int, Contact> */
class Contacts extends Collection
{
    protected function type(): string
    {
        return Contact::class;
    }
}
