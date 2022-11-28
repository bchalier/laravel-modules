<?php

namespace Bchalier\LaravelModules\App\Models;

use Illuminate\Support\Str;

class Module
{
    protected string $name;
    protected string $namespace;

    public function __construct(
        protected string $path,
    ) {}

    public function name(): string
    {
        return $this->name ??= Str::afterLast($this->path, '/');
    }

    public function namespace(): string
    {
        return $this->namespace ??= config('modules.base_namespace') . '\\' . $this->name();
    }
}
