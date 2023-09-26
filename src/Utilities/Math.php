<?php

namespace Hoonam\Framework\Utilities;

class Math
{
    public static function percent(int|float $number, int|float $percent): int|float
    {
        return ($number * $percent) / 100;
    }

    public static function truncate(int|float $number): int
    {
        return intval($number);
    }

    public static function positive(int|float $number): int|float
    {
        return max($number, 0);
    }
}
