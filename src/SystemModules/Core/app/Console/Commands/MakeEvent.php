<?php

namespace SystemModules\Core\App\Console\Commands;

use Illuminate\Foundation\Console\EventMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class MakeEvent extends EventMakeCommand
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
            $moduleName = $this->module->name;

            return $rootNamespace . "\\$moduleName\App\Events";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}