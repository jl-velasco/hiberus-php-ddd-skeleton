<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Symfony;

use Hiberus\Skeleton\Shared\Domain\Exception\AlreadyStoredException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidParamException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidPermissionsException;
use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;
use Hiberus\Skeleton\Shared\Domain\Exception\MissingParamException;
use Hiberus\Skeleton\Shared\Domain\Exception\ResourceNotFoundException;
use InvalidArgumentException;
use function Lambdish\Phunctional\get;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

final class ApiExceptionsHttpStatusCodeMapping
{
    private const DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;

    /** @var array<string, int> */
    private array $exceptions = [
        MissingParamException::class => Response::HTTP_BAD_REQUEST,
        InvalidValueException::class => Response::HTTP_BAD_REQUEST,
        InvalidParamException::class => Response::HTTP_BAD_REQUEST,
        MethodNotAllowedHttpException::class => Response::HTTP_METHOD_NOT_ALLOWED,
        AlreadyStoredException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
        ResourceNotFoundException::class => Response::HTTP_NOT_FOUND,
        InvalidPermissionsException::class => Response::HTTP_UNAUTHORIZED,
    ];

    public function register(string $exceptionClass, int $statusCode): void
    {
        $this->exceptions[$exceptionClass] = $statusCode;
    }

    public function statusCodeFor(string $exceptionClass): int
    {
        $statusCode = get($exceptionClass, $this->exceptions, self::DEFAULT_STATUS_CODE);

        if (null === $statusCode) {
            throw new InvalidArgumentException("There are no status code mapping for <{$exceptionClass}>");
        }

        return $statusCode;
    }
}
