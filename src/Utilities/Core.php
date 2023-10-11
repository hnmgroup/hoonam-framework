<?php

namespace Hoonam\Framework\Utilities;

use Hoonam\Framework\Equatable;
use Illuminate\Support\Arr;
use UnitEnum;

class Core
{
    public static function manipulate(mixed &$value, callable $transformer): void
    {
        if (!filled($value)) return;
        else if (is_array($value)) self::transformArray($value, $transformer);
        else if (is_object($value) && !self::isEnum($value)) self::transformObject($value, $transformer);
        else $value = $transformer($value);
    }

    public static function isPresent(mixed $value): bool
    {
        return !is_null($value);
    }

    public static function isAbsent(mixed $value): bool
    {
        return is_null($value);
    }

    private static function transformArray(array &$array, callable $transformer): void
    {
        $keys = array_keys($array);
        foreach ($keys as $key) {
            $value = $array[$key];
            self::manipulate($value, $transformer);
            $array[$key] = $value;
        }
    }

    private static function transformObject(object $object, callable $transformer): void
    {
        $props = get_object_vars($object);
        foreach ($props as $name => $value) {
            self::manipulate($value, $transformer);
            $object->{$name} = $value;
        }
    }

    public static function isEnum(mixed $value): bool
    {
        return $value instanceof UnitEnum;
    }

    public static function equals(mixed $x, mixed $y): bool
    {
        if (is_null($x)) return is_null($y);
        if (is_null($y)) return false;
        if ($x instanceof Equatable) return $x->equals($y);
        if ($y instanceof Equatable) return $y->equals($x);
        return $x === $y;
    }

    public static function transformValue(mixed $value, callable $interceptor, $default = null): mixed
    {
        return !is_null($value) && $value !== '' ? $interceptor($value) : $default;
    }

    public static function mergeArraysByKey(array ...$arrays): array
    {
        $result = [];
        foreach ($arrays as $array) {
            $result = $array + $result;
        }
        return $result;
    }

    public static function arrayToMap(array $array, mixed $key = null, string|callable|null $value = null): array
    {
        $result = [];
        foreach ($array as $rawKey => $rawValue) {
            $itemKey = is_null($key) ? $rawKey : self::getValue($rawValue, $key);
            $itemValue = is_null($value) ? $rawValue : (is_callable($value) ? $value($rawValue, $rawKey, $itemKey) : self::getValue($rawValue, $value));
            $result[$itemKey] = $itemValue;
        }
        return $result;
    }

    public static function getValue(mixed $value, mixed $key, mixed $default = null): mixed
    {
        if (is_null($value)) return $default;

        if ($key === '') return $value;

        if (is_string($key) && ($nestedIndex = self::str_pos($key, '.', offset: 1)) >= 0) {
            return self::getValue(
                self::getValue($value, substr($key, 0, $nestedIndex), $default),
                substr($key, $nestedIndex + 1),
                $default);
        }

        $isFunc = is_string($key) && str_ends_with($key, '()');
        if ($isFunc) $key = rtrim($key, '()');

        if (is_array($value) && array_key_exists($key, $value))
            return $isFunc ? $value[$key]() : $value[$key];
        else if (is_object($value) && property_exists($value, $key))
            return $isFunc ? $value->{$key}() : $value->{$key};
        else if (is_object($value) && method_exists($value, $key))
            return $value->{$key}();

        return $default;
    }

    public static function str_pos(string $haystack, string $needle, int $offset = 0): int
    {
        $pos = strpos($haystack, $needle, $offset);
        return $pos === false ? -1 : $pos;
    }

    public static function omitNulls(array $array): array
    {
        return collect($array)->reject(fn ($item) => is_null($item))->values()->all();
    }

    public static function omitBlanks(array $array): array
    {
        return collect($array)->reject(fn ($item) => isBlank($item))->values()->all();
    }

    public static function arrayDiffByKey(array $array1, array $array2, mixed $key): array
    {
        $result = [];
        $array2Coll = collect($array2);
        foreach ($array1 as $k => $v) {
            if ($array2Coll->contains(fn ($i) => getValue($i, $key) == $v)) continue;
            $result[$k] = $v;
        }
        return $result;
    }
}
