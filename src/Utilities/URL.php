<?php

namespace Hoonam\Framework\Utilities;

class URL
{
    /**
     * @param array{'schema'?: string, 'user'?: string, 'pass'?: string, 'host'?: string, 'port'?: int, 'path'?: string, 'query'?: array, 'fragment'?: string|array} $components
     */
    public static function build(string $url, array $components): string
    {
        $urlComponents = parse_url($url);

        $urlComponents['query'] = self::buildQuery($urlComponents['query'] ?? '', $components['query'] ?? []);

        if (isset($components['fragment'])) {
            $fragment = $components['fragment'];
            $urlComponents['fragment'] = is_array($fragment)
                ? self::buildQuery($urlComponents['fragment'] ?? '', $fragment)
                : $fragment;
        }

        return self::buildFromComponents($urlComponents);
    }

    private static function buildQuery(string $query, array $params): string
    {
        $queryParams = [];
        mb_parse_str($query, $queryParams);
        $queryParams = array_merge($queryParams, $params);

        $entries = [];
        foreach ($queryParams as $name => $value) {
            if (is_null($value)) continue;

            $hasName = is_string($name);
            if ($hasName)
                $entries[] = rawurlencode($name).'='.rawurlencode($value);
            else
                $entries[] = rawurlencode($value);
        }
        return join('&', $entries);
    }

    private static function buildFromComponents(array $components): string
    {
        $url = '';

        if (!empty($components['scheme'])) $url .= $components['scheme'].'://';
        if (!empty($components['user'])) $url .= $components['user'];
        if (!empty($components['pass'])) $url .= ':'.$components['pass'];
        if (!empty($components['user'])) $url .= '@';
        if (!empty($components['host'])) $url .= $components['host'];
        if (!empty($components['port'])) $url .= ':'.$components['port'];
        if (!empty($components['path'])) $url .= $components['path'];
        if (!empty($components['query'])) $url .= '?'.$components['query'];
        if (!empty($components['fragment'])) $url .= '#'.$components['fragment'];

        return $url;
    }
}
