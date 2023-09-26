<?php

namespace Hoonam\Framework\WebApi;

use Illuminate\Contracts\Foundation\Application;

interface EventProvider
{
    static function register(Application $app): void;
}
