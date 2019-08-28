<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\NotificationMakeCommand as BaseNotificationMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class NotificationMakeCommand extends BaseNotificationMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Notification";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}