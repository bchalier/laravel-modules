<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\ObserverMakeCommand as BaseObserverMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class ObserverMakeCommand extends BaseObserverMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Observers";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
