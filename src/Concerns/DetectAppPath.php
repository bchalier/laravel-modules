<?php

namespace Bchalier\LaravelModules\App\Concerns;

use Illuminate\Support\Str;

trait DetectAppPath
{
    protected function detectAppPath(string $path): string
    {
        return Str::before($path, '/app/') . '/app/';
    }
}
