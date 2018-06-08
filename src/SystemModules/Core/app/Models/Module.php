<?php

namespace SystemModules\Core\App\Models;

use Illuminate\Database\Eloquent\Model;
use SystemModules\Core\App\Facades\ModulesManager;

class Module extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'alias' => 'string',
        'keywords' => 'array',
        'path' => 'string',
        'loadParameters' => 'array',
        'providers' => 'array',
        'aliases' => 'array',
        'active' => 'boolean',
    ];

    /**
     * The list of the system modules aliases, this modules can't be altered.
     *
     * @var array
     */
    public $sysModules = [
        'core',
    ];

    /**
     * Execute a query for a single record by alias.
     *
     * @param $alias string
     * @return Module
     */
    public static function findAlias($alias)
    {
        return static::where('alias', $alias)->first();
    }

    /**
     * Execute a query for a single record by alias or throw an exception.
     *
     * @param $alias string
     * @return Module
     */
    public static function findAliasOrFail($alias)
    {
        return static::where('alias', $alias)->firstOrFail();
    }

    /**
     * Return true if the module is active
     *
     * @return boolean
     */
    public function isActive()
    {
        if ($this->active)
            return true;
        else
            return false;
    }

    /**
     * Return true if it's a system module
     *
     * @return boolean
     */
    public function isSystem()
    {
        if (in_array($this->alias, $this->sysModules))
            return true;
        else
            return false;
    }

    /**
     * Enable the module
     *
     * @return boolean
     */
    public function enable()
    {
        $this->active = true;
        return $this->save();
    }

    /**
     * Disable the module
     *
     * @return boolean
     */
    public function disable()
    {
        $this->active = false;
        return $this->save();
    }

    /**
     * Delete the module
     *
     * @return boolean
     * @throws \Exception
     */
    public function delete()
    {
        if (parent::delete()) {
            return ModulesManager::delete($this);
        }
    }
}