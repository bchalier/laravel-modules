<?php

namespace SystemModules\Core\App\Console\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class MakeResource extends ResourceMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Http\Resources";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}