<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Routing\Console\MiddlewareMakeCommand as BaseMiddlewareMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class MiddlewareMakeCommand extends BaseMiddlewareMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Http\Middleware";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}