<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\ProviderMakeCommand as BaseProviderMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class ProviderMakeCommand extends BaseProviderMakeCommand
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

            return $rootNamespace . "\\$moduleName\App\Providers";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }

    public function afterHandle()
    {
        $className = $this->getNameInput();
        $class = $this->qualifyClass($className);

        if ($this->module->setConfig("providers.$className", $class)) {
            $this->info("Provider $className added to module " . $this->module->name . "'s composer.json");
        }
    }
}