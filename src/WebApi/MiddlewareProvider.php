<?php

namespace Hoonam\Framework\WebApi;

interface MiddlewareProvider
{
    /** @return string[] */
    public static function middlewares(): array;

    /** @return array<string, string> */
    public static function middlewareAliases(): array;
}
