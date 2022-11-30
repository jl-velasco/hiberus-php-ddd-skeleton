<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain;

interface Logger
{
    /** @param array<mixed> $context */
    public function info(string $message, array $context = []): void;

    /** @param array<mixed> $context */
    public function warning(string $message, array $context = []): void;

    /** @param array<mixed> $context */
    public function error(string $message, array $context = []): void;
}
