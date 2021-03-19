<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Bchalier\SystemModules\Core\App\Console\Commands\Concerns\ExtendMakeCommand;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    use ExtendMakeCommand;

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($this->module) {
            $moduleName = $this->module->getBaseNamespace();

            return $rootNamespace . "\\$moduleName\App\Http\Controllers";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (! Str::startsWith($model, [$rootNamespace = $this->laravel->getNamespace(), 'Modules\\'])) {
            $model = $rootNamespace . $model;
        }

        return $model;
    }
}
