<?php
declare(strict_types = 1);

namespace Hiberus\Skeleton\Ticket\Domain;

use Hiberus\Skeleton\Shared\Domain\Bus\Event\EventSourcedEntity;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Date;
use Hiberus\Skeleton\Shared\Domain\ValueObject\Uuid;
use Hiberus\Skeleton\Ticket\Domain\Events\TicketCommentCreatedDomainEvent;

class Comment extends EventSourcedEntity
{
    public function __construct(
        private readonly Uuid $id,
        private readonly Uuid $userId,
        private readonly Title $title,
        private readonly Description $description,
        private readonly Date $createdAt = new Date(),
    ) {
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function userId(): Uuid
    {
        return $this->userId;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): Description
    {
        return $this->description;
    }

    public function createdAt(): Date
    {
        return $this->createdAt;
    }
}