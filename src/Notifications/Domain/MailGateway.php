<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Notifications\Domain;

interface MailGateway
{
    public function send(MailTemplate $mail): void;
}
