<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Notifications\Application;

use Hiberus\Skeleton\Notifications\Domain\Contact;
use Hiberus\Skeleton\Notifications\Domain\Contacts;
use Hiberus\Skeleton\Notifications\Domain\MailGateway;
use Hiberus\Skeleton\Notifications\Domain\MailTemplate;
use Hiberus\Skeleton\Registration\Domain\Events\UserCreatedDomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEvent;
use Hiberus\Skeleton\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Email;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Name;

class UserCreatedSendEmailConsumer implements DomainEventSubscriber
{
    private const QUEUE_NAME = 'hiberus_skeleton.user_created.send_email';

    public function __construct(
        private readonly MailGateway $mailGateway,
    ) {
    }

    /** @throws InvalidValueException */
    public function __invoke(DomainEvent $event): void
    {
        try {
            $data = $event->toPrimitives();
            $contact = new Contact(
                new Email($data['email']),
                new Name($data['name'])
            );
            $contacts = new Contacts();
            $contacts->add($contact);

            $subject = 'Hello ' . $contact->name()->value();

            $mail = new MailTemplate('Thanks for registration', $subject, $contacts);
            $this->mailGateway->send($mail);
        } catch (\Throwable $e) {
            throw new InvalidValueException('Error sending email: ' . $e->getMessage());
        }
    }

    public static function subscribedTo(): array
    {
        return [UserCreatedDomainEvent::class];
    }

    public static function queue(): string
    {
        return self::QUEUE_NAME;
    }
}
