<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\ProviderMakeCommand as BaseProviderMakeCommand;
use Bchalier\SystemModules\Core\App\Console\Commands\Concerns\ExtendMakeCommand;

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
            $moduleName = $this->module->getBaseNamespace();

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
            $this->info("Provider $className added to module {$this->module->getNameOrAlias()}'s composer.json");
        }
    }
}
