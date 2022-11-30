<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Notifications\Domain;

class MailTemplate
{
    public function __construct(
        private readonly string $message,
        private readonly string $subject,
        private readonly Contacts $contacts,
    ) {
    }

    public function message(): string
    {
        return $this->message;
    }

    public function subject(): string
    {
        return $this->subject;
    }

    public function contacts(): Contacts
    {
        return $this->contacts;
    }
}
