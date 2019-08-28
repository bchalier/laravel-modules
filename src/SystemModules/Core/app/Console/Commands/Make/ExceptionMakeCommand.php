<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\ExceptionMakeCommand as BaseExceptionMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

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