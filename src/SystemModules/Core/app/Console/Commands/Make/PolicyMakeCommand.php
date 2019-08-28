<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\PolicyMakeCommand as BasePolicyMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class PolicyMakeCommand extends BasePolicyMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Policies";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
