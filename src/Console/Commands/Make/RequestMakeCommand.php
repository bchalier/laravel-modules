<?php

namespace Bchalier\LaravelModules\App\Console\Commands\Make;

use Bchalier\LaravelModules\App\Console\Commands\Concerns\ExtendMakeCommand;
use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;

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
            $moduleName = $this->module->getBaseNamespace();

            return $rootNamespace . "\\$moduleName\App\Http\Requests";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
