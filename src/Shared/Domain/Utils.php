<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain;

use ReflectionClass;
use RuntimeException;

final class Utils
{
    public static function endsWith(string $needle, string $haystack): bool
    {
        $length = \strlen($needle);
        if ($length === 0) {
            return true;
        }

        return substr($haystack, -$length) === $needle;
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text) ? $text : strtolower(preg_replace('/([^A-Z\s])([A-Z])/', '$1_$2', $text));
    }

    public static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace('_', '', ucwords($text, '_')));
    }

    public static function extractClassName(object $object): string
    {
        $reflect = new ReflectionClass($object);

        return $reflect->getShortName();
    }

    /**
     * @param array<string, mixed> $array
     *
     * @return array<string, string>
     */
    public static function dot(array $array, string $prepend = ''): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (\is_array($value) && !empty($value)) {
                $results = array_merge($results, Utils::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /** @param array<string, mixed> $values */
    public static function jsonEncode(array $values): string
    {
        return (string) json_encode($values);
    }

    /** @return array<mixed> */
    public static function jsonDecode(string $json): array
    {
        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
        }

        return $data;
    }
}
