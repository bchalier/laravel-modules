<?php

namespace Bchalier\SystemModules\Core\App\Factories;

use Illuminate\Support\Str;

abstract class Factory extends \Illuminate\Database\Eloquent\Factories\Factory
{
    /**
     * Get the factory name for the given model name.
     *
     * @param  string  $modelName
     * @return string
     */
    public static function resolveFactoryName(string $modelName)
    {
        return Str::startsWith($modelName, 'Modules\\')
            ? self::resolveModuleFactoryName($modelName)
            : parent::resolveFactoryName($modelName);
    }

    protected static function resolveModuleFactoryName(string $modelName)
    {
        $resolver = static::$factoryNameResolver ?: function (string $modelReference) {
            $modelName = Str::after($modelReference, 'App\\Models\\');

            return Str::before($modelReference, 'App\\') . static::$namespace . $modelName . 'Factory';
        };

        return $resolver($modelName);
    }
}
