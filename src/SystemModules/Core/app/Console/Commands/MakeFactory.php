<?php

namespace SystemModules\Core\App\Console\Commands;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class MakeFactory extends FactoryMakeCommand
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

            return $rootNamespace . "\\$moduleName\database\\factories";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}