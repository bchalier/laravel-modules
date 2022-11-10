<?php

namespace Bchalier\LaravelModules\App\Contracts;

use Illuminate\Contracts\Foundation\Application;

interface CanBoot
{
    public function boot(Application $app): void;
}
