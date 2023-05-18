<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\App\Controller\Registration;

use Hiberus\Skeleton\Registration\Application\Upsert\UpsertUserCommand;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\Exception\MissingParamException;
use Hiberus\Skeleton\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserPutController extends ApiController
{
    /** @throws MissingParamException */
    public function __invoke(string $id, Request $request): Response
    {
        $data = $this->dataFromRequest($request);
        $this->dispatch(
            new UpsertUserCommand(
                $id,
                $data['name'],
                $data['email'],
                $data['password'],
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
        return ['name', 'email', 'password'];
    }
}
