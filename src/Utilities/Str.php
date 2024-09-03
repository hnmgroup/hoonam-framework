<?php

namespace Hoonam\Framework\Utilities;

use Illuminate\Support\Str as ILStr;

class Str
{
    public static function trim(?string $str): ?string
    {
        return self::nonBlank($str) ? trim($str) : null;
    }

    public static function isBlank(?string $str): bool
    {
        return is_null($str) || strlen(trim($str)) == 0;
    }

    public static function isEmpty(?string $str): bool
    {
        return is_null($str) || strlen($str) == 0;
    }

    public static function nonBlank(?string $str): bool
    {
        return !self::isBlank($str);
    }

    public static function nonEmpty(?string $str): bool
    {
        return !self::isEmpty($str);
    }

    /**
     * @return string[]
     */
    public static function splitNonEmpty(?string $str, string $separator): array
    {
        if (self::isEmpty($str)) return [];
        $parts = collect(explode($separator, $str))->filter(fn ($s) => self::nonEmpty($s));
        return $parts->values()->all();
    }

    public static function generateUniqueId(): string
    {
        return ILStr::replace('-', '', ILStr::uuid()->toString());
    }

    /**
     * converts persian and arabic native digits to latin numeric digits
     */
    public static function sanitizeDigits(string $str): ?string
    {
        if (self::isBlank($str)) return $str;
        $NON_STANDARD_DIGITS_PATTERN = '/[\x{06F0}-\x{06F9}\x{0660}-\x{0669}]/u';
        return preg_replace_callback(
            $NON_STANDARD_DIGITS_PATTERN,
            function ($match) {
                $code = mb_ord($match[0]);
                if ($code >= 0x06F0 && $code <= 0x06F9) return chr($code - 1728);
                elseif ($code >= 0x0660 && $code <= 0x0669) return chr($code - 1584);
                else return $match[0];
            },
            $str,
        );
    }
}
