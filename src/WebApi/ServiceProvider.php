<?php

namespace Hoonam\Framework\WebApi;

use Illuminate\Contracts\Foundation\Application;

interface ServiceProvider
{
    static function register(Application $app): void;
}
