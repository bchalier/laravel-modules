<?php

namespace Bchalier\LaravelModules\App\Facades;

use Bchalier\LaravelModules\App\Models\Module;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Module[] getActiveModules($andSystem = false)
 * @method static bool install(string $path, bool $disabled = false)
 * @method static bool uninstall(Module $module)
 * @method static bool delete(Module $module)
 *
 * @see \Bchalier\LaravelModules\App\Services\ModulesManager
 */
class ModulesManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return \Bchalier\LaravelModules\App\Services\ModulesManager::class;
    }
}
