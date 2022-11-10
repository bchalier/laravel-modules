<?php

namespace Bchalier\LaravelModules\App\Concerns;

use Illuminate\Support\Str;

trait DetectAppPath
{
    protected function detectAppPath(): string
    {
        return Str::before(__DIR__, '/app/') . '/app/';
    }
}
