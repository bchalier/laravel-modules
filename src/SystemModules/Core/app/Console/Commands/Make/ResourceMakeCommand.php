<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\ResourceMakeCommand as BaseResourceMakeCommand;
use Bchalier\SystemModules\Core\App\Console\Commands\Concerns\ExtendMakeCommand;

class ResourceMakeCommand extends BaseResourceMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Http\Resources";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
