<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\JobMakeCommand as BaseJobMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class JobMakeCommand extends BaseJobMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Jobs";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}