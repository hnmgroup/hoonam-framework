<?php

namespace Hoonam\Framework\WebApi;

use Illuminate\Contracts\Foundation\Application;

interface ServiceProvider
{
    public static function register(Application $app): void;
}
