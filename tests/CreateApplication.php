<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

trait CreateApplication
{
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/bootstrap.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
