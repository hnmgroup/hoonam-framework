<?php

namespace Hoonam\Framework\WebApi;

interface MiddlewareProvider
{
    /** @return array<string, string> */
    public static function middlewares(): array;
}
