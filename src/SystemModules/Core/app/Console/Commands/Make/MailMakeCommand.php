<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\MailMakeCommand as BaseMailMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class MailMakeCommand extends BaseMailMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Mail";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}