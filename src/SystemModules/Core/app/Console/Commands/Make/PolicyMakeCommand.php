<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\PolicyMakeCommand as BasePolicyMakeCommand;
use Bchalier\SystemModules\Core\App\Console\Commands\Concerns\ExtendMakeCommand;

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
