<?php

namespace Hoonam\Framework\Utilities;

class URL
{
    public static function appendParameter(string $url, string $name, mixed $value): string
    {
        $query = parse_url($url, PHP_URL_QUERY);
        if (is_null($query))
            $url .= '?'.$name.'='.$value;
        else if ($query === '')
            $url .= $name.'='.$value;
        else
            $url .= '&'.$name.'='.$value;

        return $url;
    }
}
