<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class RequestMakeCommand extends BaseRequestMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Http\Requests";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}