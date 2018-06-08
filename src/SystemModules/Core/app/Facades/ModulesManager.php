<?php

namespace SystemModules\Core\App\Facades;

use Illuminate\Support\Facades\Facade;
use SystemModules\Core\App\Models\Module;

/**
 * @method static \SystemModules\Core\App\Services\ModulesManager getActiveModules()
 * @method static \SystemModules\Core\App\Services\ModulesManager install(string $path, bool $disabled)
 * @method static \SystemModules\Core\App\Services\ModulesManager delete(Module $module)
 *
 * @see \SystemModules\Core\App\Services\ModulesManager
 */

class ModulesManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \SystemModules\Core\App\Services\ModulesManager::class;
    }
}
