<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Database\Console\Factories\FactoryMakeCommand as BaseFactoryMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class FactoryMakeCommand extends BaseFactoryMakeCommand
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

            return $rootNamespace . "\\$moduleName\database\\factories";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}