<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Infrastructure\Symfony;

use Hiberus\Skeleton\Shared\Domain\Utils;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;


final class FlashSession
{
    /** @var array<string, string> */
    private static array $flashes = [];

    public function __construct(RequestStack $requestStack)
    {
        try {
            /** @var Session $session */
            $session = $requestStack->getSession();
            self::$flashes = Utils::dot($session->getFlashBag()->all());
        } catch (SessionNotFoundException $e) {
        }
    }



    public function get(string $key, string $default = null): string
    {
        if (array_key_exists($key, self::$flashes)) {
            return self::$flashes[$key];
        }

        if (array_key_exists($key . '.0', self::$flashes)) {
            return self::$flashes[$key . '.0'];
        }

        if (array_key_exists($key . '.0.0', self::$flashes)) {
            return self::$flashes[$key . '.0.0'];
        }

        return $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, self::$flashes)
            || array_key_exists($key . '.0', self::$flashes)
            || array_key_exists($key . '.0.0', self::$flashes);
    }
}