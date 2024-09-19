<?php

namespace Hoonam\Framework\Utilities;

use UnitEnum;
use DateTimeInterface;
use InvalidArgumentException;

class Convert
{
    public static function toInteger(mixed $value, bool $throwOnBlank = true): ?int
    {
        if (is_null($value)) {
            if (!$throwOnBlank) return null;
            throw new InvalidArgumentException('can not convert null to integer');
        }

        if (is_float($value) || is_integer($value) || is_bool($value)) return intval($value);

        if (!is_string($value))
            throw new InvalidArgumentException('can not convert object to integer');

        $trimValue = trim($value);

        if ($trimValue === '') {
            if (!$throwOnBlank) return null;
            throw new InvalidArgumentException('can not convert \''.$value.'\' to integer');
        }

        if (preg_match('/^\d+$/', $trimValue) !== 1)
            throw new InvalidArgumentException('can not convert to integer: \''.$value.'\'');

        return intval($trimValue);
    }

    public static function toFloat(mixed $value, bool $throwOnBlank = true): ?float
    {
        if (is_null($value)) {
            if (!$throwOnBlank) return null;
            throw new InvalidArgumentException('can not convert null to float');
        }

        if (is_float($value) || is_integer($value) || is_bool($value)) return floatval($value);

        if (!is_string($value))
            throw new InvalidArgumentException('can not convert object to float');

        $trimValue = trim($value);

        if ($trimValue === '') {
            if (!$throwOnBlank) return null;
            throw new InvalidArgumentException('can not convert \''.$value.'\' to float');
        }

        if (preg_match('/^(\d+\.?\d*|\.\d+)$/', $trimValue) !== 1)
            throw new InvalidArgumentException('can not convert to float: \''.$value.'\'');

        return floatval($trimValue);
    }

    public static function toBoolean(mixed $value, bool $throwOnBlank = true): ?bool
    {
        if (is_null($value)) {
            if (!$throwOnBlank) return null;
            throw new InvalidArgumentException('can not convert null to boolean');
        }

        if (is_float($value) || is_integer($value) || is_bool($value)) return boolval($value);

        if (!is_string($value))
            throw new InvalidArgumentException('can not convert object to boolean');

        $trimValue = strtolower(trim($value));

        if ($trimValue === '') {
            if (!$throwOnBlank) return null;
            throw new InvalidArgumentException('can not convert \''.$value.'\' to boolean');
        }

        if ($trimValue !== 'true' && $trimValue !== 'false' && $trimValue !== '0' && $trimValue !== '1')
            throw new InvalidArgumentException('can not convert to boolean: \''.$value.'\'');

        return $trimValue === 'true' || $trimValue === '1';
    }

    public static function toString(mixed $value): ?string
    {
        if (is_null($value)) return null;
        if (is_enum($value)) return cast($value, UnitEnum::class)->name;
        if (is_bool($value)) return $value ? 'true' : 'false';
        if (is_time($value)) return cast($value, DateTimeInterface::class)->format('H:i:s.u');
        if (is_date($value)) return cast($value, DateTimeInterface::class)->format('Y-m-d');
        if (is_date_time($value)) return cast($value, DateTimeInterface::class)->format('Y-m-d\TH:i:s.up');
        return strval($value);
    }
}
