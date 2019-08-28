<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\RuleMakeCommand as BaseRuleMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class RuleMakeCommand extends BaseRuleMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Rules";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}