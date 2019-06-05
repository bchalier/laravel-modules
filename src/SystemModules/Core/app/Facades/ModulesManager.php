<?php

namespace SystemModules\Core\App\Facades;

use Illuminate\Support\Facades\Facade;
use SystemModules\Core\App\Models\Module;

/**
 * @method static Module[] getActiveModules()
 * @method static bool install(string $path, bool $disabled = false)
 * @method static bool uninstall(Module $module)
 * @method static bool delete(Module $module)
 *
 * @inheritDoc \SystemModules\Core\App\Services\ModulesManager
 */

class ModulesManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SystemModules\Core\App\Services\ModulesManager::class;
    }
}
