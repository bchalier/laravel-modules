<?php

namespace SystemModules\Core\App\Facades;

use Illuminate\Support\Facades\Facade;
use SystemModules\Core\App\Models\Module;

/**
 * @method static Module[] getActiveModules($andSystem = false)
 * @method static bool install(string $path, bool $disabled = false)
 * @method static bool uninstall(Module $module)
 * @method static bool delete(Module $module)
 *
 * @see \SystemModules\Core\App\Services\ModulesManager
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
        return \SystemModules\Core\App\Services\ModulesManager::class;
    }
}
