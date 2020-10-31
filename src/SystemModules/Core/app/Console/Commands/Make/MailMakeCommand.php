<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\MailMakeCommand as BaseMailMakeCommand;
use Bchalier\SystemModules\Core\App\Console\Commands\Concerns\ExtendMakeCommand;

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
            $moduleName = $this->module->getBaseNamespace();

            return $rootNamespace . "\\$moduleName\App\Mail";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}