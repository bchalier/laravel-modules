<?php

namespace Bchalier\LaravelModules\App\Console\Commands\Make;

use Bchalier\LaravelModules\App\Console\Commands\Concerns\ExtendMakeCommand;
use Illuminate\Foundation\Console\ExceptionMakeCommand as BaseExceptionMakeCommand;

class ExceptionMakeCommand extends BaseExceptionMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Exceptions";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
