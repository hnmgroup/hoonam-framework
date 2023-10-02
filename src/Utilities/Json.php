<?php

namespace Hoonam\Framework\Utilities;

use RuntimeException;

class Json
{
    const DEFAULT_JSON_FLAGS =
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_PRESERVE_ZERO_FRACTION |
        JSON_INVALID_UTF8_SUBSTITUTE |
        JSON_PARTIAL_OUTPUT_ON_ERROR;

    public static function encode(mixed $value, bool $prettyPrint = false): string
    {
        $json = json_encode($value, self::DEFAULT_JSON_FLAGS | ($prettyPrint ? JSON_PRETTY_PRINT : 0));

        if ($json === false) {
            $errorCode = json_last_error();
            $msg = match ($errorCode) {
                JSON_ERROR_DEPTH          => 'Maximum stack depth exceeded',
                JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
                JSON_ERROR_CTRL_CHAR      => 'Unexpected control character found',
                JSON_ERROR_UTF8           => 'Malformed UTF-8 characters, possibly incorrectly encoded',
                default                   => 'Unknown error',
            };
            throw new RuntimeException('JSON encoding failed: '.$msg);
        }

        return $json;
    }

    public static function decode(string $json, ?bool $associative = true): mixed
    {
        return json_decode($json, $associative);
    }
}
