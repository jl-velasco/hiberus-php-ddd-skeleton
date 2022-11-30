<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Symfony;

use Hiberus\Skeleton\Shared\Domain\Bus\Command\Command;
use Hiberus\Skeleton\Shared\Domain\Bus\Command\CommandBus;
use Hiberus\Skeleton\Shared\Domain\Bus\Query\Query;
use Hiberus\Skeleton\Shared\Domain\Bus\Query\QueryBus;
use Hiberus\Skeleton\Shared\Domain\Bus\Query\Response;
use Hiberus\Skeleton\Shared\Domain\Exception\MissingParamException;
use Hiberus\Skeleton\Shared\Domain\Utils;
use function Lambdish\Phunctional\each;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
        ApiExceptionsHttpStatusCodeMapping $exceptionHandler
    ) {
        each(
            fn (int $httpCode, string $exceptionClass) => $exceptionHandler->register($exceptionClass, $httpCode),
            $this->exceptions()
        );
    }

    /** @return array<string, int> */
    abstract protected function exceptions(): array;

    protected function ask(Query $query): ?Response
    {
        return $this->queryBus->ask($query);
    }

    protected function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }

    /** @return array<int, string> */
    abstract protected function mandatoryParams(): array;

    /**
     * @throws MissingParamException
     *
     * @return array<string, string>
     */
    protected function dataFromRequest(Request $request): array
    {
        $data = Utils::jsonDecode($request->getContent());
        $errors = array_filter($this->mandatoryParams(), static fn ($param) => !isset($data[$param]));
        if ($errors) {
            throw new MissingParamException(implode(',', $errors));
        }

        return $data;
    }
}
