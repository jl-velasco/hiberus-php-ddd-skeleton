<?php
declare(strict_types = 1);

namespace Hiberus\Skeleton\Ticket\Application;

use Hiberus\Skeleton\Shared\Domain\Bus\Command\Command;

class UpsertTicketCommand  implements Command
{
    public function __construct(
        private readonly string $id,
        private readonly string $userId,
        private readonly string $commentId,
        private readonly string $commentTitle,
        private readonly string $commentDescription,
        private readonly ?string $status = null,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function status(): ?string
    {
        return $this->status;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function commentId(): string
    {
        return $this->commentId;
    }

    public function commentTitle(): string
    {
        return $this->commentTitle;
    }

    public function commentDescription(): string
    {
        return $this->commentDescription;
    }
}