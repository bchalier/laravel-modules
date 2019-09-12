<?php

namespace SystemModules\Core\App\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use SystemModules\Core\App\Facades\ModulesManager;

class Module
{
    public $name;
    public $alias;
    public $path;
    public $loadParameters;
    public $providers = [];
    public $aliases = [];
    public $active;

    public $isSystem = false;

    public function __construct(array $config = [])
    {
        foreach ($config as $key => $param) {
            if (property_exists($this, $key)) {
                $this->$key = $param;
            }
        }
    }

    /**
     * Execute a query for a single record by alias.
     *
     * @param $alias string
     * @return Module
     */
    public static function findAlias($alias): ?Module
    {
        return self::where('alias', strtolower($alias))->first();
    }

    public static function where(string $key, $value): Collection
    {
        return self::all()->where($key, $value);
    }

    public static function all(): Collection
    {
        return self::loadFromConfig(ModulesManager::getModulesConfig());
    }

    public static function system(): Collection
    {
        return self::loadFromConfig(ModulesManager::getSystemModulesConfig(), true);
    }

    protected static function loadFromConfig(array $config, bool $isSystem = false): Collection
    {
        $modules = [];

        foreach ($config as $moduleConfig) {
            if ($moduleConfig['path']) {
                $module = self::loadFromPath($moduleConfig);
                $module->isSystem = $isSystem;
                $modules[] = $module;
            }
        }

        return collect($modules);
    }

    protected static function loadFromPath(array $config): Module
    {
        $configFilePath = base_path($config['path'] . 'composer.json');
        $configFile = file_get_contents($configFilePath);
        $moduleConfig = json_decode($configFile, true);
        $config = array_merge($config, Arr::get($moduleConfig, 'extra.laravel-modules'));

        return new Module($config);
    }

    /**
     * Update the module config in composer.json under extra.laravel-modules.
     *
     * @param $config
     * @param $value
     * @return bool
     */
    public function setConfig($config, $value): bool
    {
        return ModulesManager::setConfig($this, $config, $value);
    }

    /**
     * Set path in global config
     *
     * @param $path
     * @return mixed
     */
    public function setPath($path): bool
    {
        $this->path = $path;
        return ModulesManager::setGlobalConfig($this->alias, 'path', $path);
    }

    /**
     * Return true if the module is active
     *
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Return true if it's a system module
     *
     * @return boolean
     */
    public function isSystem(): bool
    {
        return $this->isSystem;
    }

    /**
     * Get the alias of the module.
     *
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getNameOrAlias(): string
    {
        return $this->name ?? $this->alias;
    }

    public function getBaseNamespace(): string
    {
        return ucfirst($this->alias);
    }

    /**
     * Enable the module
     *
     * @return boolean
     */
    public function enable(): bool
    {
        $this->active = true;
        return ModulesManager::setGlobalConfig($this->alias, 'active', true);
    }

    /**
     * Disable the module
     *
     * @return boolean
     */
    public function disable(): bool
    {
        $this->active = false;
        return ModulesManager::setGlobalConfig($this->alias, 'active', false);
    }

    /**
     * Uninstall the module
     *
     * @return bool|\SystemModules\Core\App\Services\ModulesManager
     * @throws \Exception
     */
    public function uninstall(): bool
    {
        return ModulesManager::dropGlobalConfig($this->alias);
    }

    /**
     * Delete the module
     *
     * @return bool|\SystemModules\Core\App\Services\ModulesManager|null
     * @throws \Exception
     */
    public function delete(): bool
    {
        return self::uninstall() ? ModulesManager::delete($this) : false;
    }

    /**
     * @param $path
     * @return string
     */
    public function path($path = ''): string
    {
        return base_path($this->path . $path);
    }
}