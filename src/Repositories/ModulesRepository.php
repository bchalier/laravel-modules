<?php

namespace Bchalier\LaravelModules\App\Repositories;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Bchalier\LaravelModules\App\Models\Module;

class ModulesRepository
{
    public function __construct(
        protected Filesystem $files
    ) {}

    /**
     * @return Collection|Module[]
     */
    public function all(): Collection
    {
        return collect($this->files->directories(base_path('modules')))
            ->map(function ($path) {
                return new Module($path);
            });
    }
}
