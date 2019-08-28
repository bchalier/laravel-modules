<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\EventMakeCommand as BaseEventMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class EventMakeCommand extends BaseEventMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Events";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}