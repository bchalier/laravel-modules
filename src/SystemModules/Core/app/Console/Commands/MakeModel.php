<?php

namespace SystemModules\Core\App\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class MakeModel extends ModelMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Models";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}