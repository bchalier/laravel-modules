<?php

namespace Bchalier\LaravelModules\App\Concerns;

use Illuminate\Support\Str;

trait DetectAppNamespace
{
    protected function detectAppNamespace(): string
    {
        return Str::before(static::class, '\\App\\') . '\\App\\';
    }
}
