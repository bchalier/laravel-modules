<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\ListenerMakeCommand as BaseListenerMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class ListenerMakeCommand extends BaseListenerMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Listener";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}