<?php

namespace Hoonam\Framework\WebApi;

use Illuminate\Contracts\Foundation\Application;

interface EventProvider
{
    public static function register(Application $app): void;
}
