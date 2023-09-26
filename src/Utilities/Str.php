<?php

namespace Hoonam\Framework\Utilities;

use Illuminate\Support\Str as ILStr;

class Str
{
    public static function valueOf(?string $str): ?string
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
     * converts persian, arabic native digits to latin numeric digits
     * @param bool $arabicKafYa fix arabic letter kaf and ya
     * @return string
     */
    public static function sanitizeText(string $text, bool $arabicKafYa = true): string
    {
        if (self::isBlank($text)) return $text;

        $len = mb_strlen($text);
        $chars = [];
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            $code = mb_ord($char);
            if ($code >= 1632 && $code <= 1641)
                $chars[] = mb_chr($code - 1584);
            else if ($code >= 1776 && $code <= 1785)
                $chars[] = mb_chr($code - 1728);
            else if ($arabicKafYa && $code == 1603)
                $chars[] = mb_chr(1705);
            else if ($arabicKafYa && $code == 1610)
                $chars[] = mb_chr(1740);
            else
                $chars[] = $char;
        }
        return implode('', $chars);
    }
}
