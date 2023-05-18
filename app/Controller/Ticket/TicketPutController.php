<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\App\Controller\Ticket;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\Exception\MissingParamException;
use Hiberus\Skeleton\Shared\Infrastructure\Symfony\ApiController;
use Hiberus\Skeleton\Ticket\Application\UpsertTicketCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketPutController extends ApiController
{
    /** @throws MissingParamException */
    public function __invoke(string $id, Request $request): Response
    {
        $data = $this->dataFromRequest($request);
        $this->dispatch(
            new UpsertTicketCommand(
                $id,
                $data['user_id'],
                $data['comment_id'],
                $data['comment_title'],
                $data['comment_description'],
                $data['status'] ?? null
            )
        );

        return new Response(status: Response::HTTP_CREATED);
    }

    /** @return array<string, int> */
    protected function exceptions(): array
    {
        return [InvalidValueException::class => Response::HTTP_BAD_REQUEST];
    }

    protected function mandatoryParams(): array
    {
        return ['user_id', 'comment_id', 'comment_title', 'comment_description'];
    }
}
