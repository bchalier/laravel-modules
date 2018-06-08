<?php

namespace SystemModules\Core\App\Console\Commands;

use Illuminate\Foundation\Console\MailMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class MakeMail extends MailMakeCommand
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