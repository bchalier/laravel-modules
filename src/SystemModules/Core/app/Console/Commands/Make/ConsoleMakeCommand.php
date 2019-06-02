<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Foundation\Console\ConsoleMakeCommand as BaseConsoleMakeCommand;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class ConsoleMakeCommand extends BaseConsoleMakeCommand
{
    use ExtendMakeCommand;

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function afterHandle()
    {
        if ($this->module && !$this->files->exists($this->module->path.'app/Console/Kernel.php')) {
            $className = $this->argument('name');
            $module = $this->module->name;

            $stub = $this->files->get($this->getKernelStub());
            $this->replaceModule($stub, $module)->replaceClassName($stub, $className);

            $this->files->put($this->module->path.'App/Console/Kernel.php', $stub);
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getKernelStub()
    {
        return base_path('vendor/bchalier/laravel-modules/src/SystemModules/Core/app/Console/Commands/stubs/console-kernel.stub');
    }

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

            return $rootNamespace . "\\$moduleName\App\Console\Commands";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
