<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Notifications\Infrastructure;

use Hiberus\Skeleton\Notifications\Domain\MailGateway;
use Hiberus\Skeleton\Notifications\Domain\MailTemplate;

class MailChimpMailGateway implements MailGateway
{
    public function send(MailTemplate $mail): void
    {
        // TODO: Implement send() method.
    }
}
