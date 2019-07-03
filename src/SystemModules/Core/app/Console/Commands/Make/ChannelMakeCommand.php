<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\ChannelMakeCommand as BaseChannelMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class ChannelMakeCommand extends BaseChannelMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Broadcasting";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
