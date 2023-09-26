<?php

use Hoonam\Framework\Domain\ApplicationException;
use Hoonam\Framework\Utilities\Convert;
use Hoonam\Framework\Utilities\Core;
use Hoonam\Framework\Utilities\Math;
use Hoonam\Framework\Utilities\Str;
use Hoonam\Framework\Utilities\Web;
use Illuminate\Support\Facades\Log;

function arrayToMap(array $array, mixed $key = null, string|callable|null $value = null): array
{
    return Core::arrayToMap($array, $key, $value);
}

function getValue(mixed $value, mixed $key, mixed $default = null): mixed
{
    return Core::getValue($value, $key, $default);
}

function str_pos(string $haystack, string $needle, int $offset = 0): int
{
    return Core::str_pos($haystack, $needle, $offset);
}

function isPresent(mixed $value): bool
{
    return Core::isPresent($value);
}

function isAbsent(mixed $value): bool
{
    return Core::isAbsent($value);
}

function mergeArraysByKey(array ...$arrays): array
{
    return Core::mergeArraysByKey(...$arrays);
}

function transformValue(mixed $value, callable $interceptor, $default = null): mixed
{
    return Core::transformValue($value, $interceptor, $default);
}

function equals(mixed $x, mixed $y): bool
{
    return Core::equals($x, $y);
}

function omitNulls(array $array): array
{
    return Core::omitNulls($array);
}

function omitBlanks(array $array): array
{
    return Core::omitBlanks($array);
}

function percent(int|float $number, int|float $percent): int|float
{
    return Math::percent($number, $percent);
}

function positive(int|float $number): int|float
{
    return Math::positive($number);
}

function truncate(int|float $number): int
{
    return Math::truncate($number);
}

function valueOf(?string $str): ?string
{
    return Str::valueOf($str);
}

function isBlank(?string $str): bool
{
    return Str::isBlank($str);
}

function isEmpty(?string $str): bool
{
    return Str::isEmpty($str);
}

function nonBlank(?string $str): bool
{
    return Str::nonBlank($str);
}

function nonEmpty(?string $str): bool
{
    return Str::nonEmpty($str);
}

function generateUniqueId(): string
{
    return Str::generateUniqueId();
}

function toInteger(mixed $value, bool $throwOnBlank = true): ?int
{
    return Convert::toInteger($value, $throwOnBlank);
}

function toFloat(mixed $value, bool $throwOnBlank = true): ?float
{
    return Convert::toFloat($value, $throwOnBlank);
}

function toBoolean(mixed $value, bool $throwOnBlank = true): ?bool
{
    return Convert::toBoolean($value, $throwOnBlank);
}

function toString(mixed $value): ?string
{
    return Convert::toString($value);
}

function contentStatus(mixed $value): int
{
    return Web::contentStatus($value);
}

function logError(Throwable $error): void
{
    $context = $error instanceof ApplicationException ? $error->context() : [];
    Log::error($error, $context);
}
