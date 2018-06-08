<?php

namespace SystemModules\Core\App\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class MakeController extends ControllerMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Http\Controller";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}