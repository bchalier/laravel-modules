<?php

namespace Bchalier\LaravelModules\App\Console\Commands\Make;

use Bchalier\LaravelModules\App\Console\Commands\Concerns\ExtendMakeCommand;
use Illuminate\Foundation\Console\ConsoleMakeCommand as BaseConsoleMakeCommand;

class ConsoleMakeCommand extends BaseConsoleMakeCommand
{
    use ExtendMakeCommand;

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function afterHandle()
    {
        if ($this->module && !$this->files->exists($this->module->path('app/Console/Kernel.php'))) {
            $className = $this->argument('name');
            $module = $this->module->getBaseNamespace();

            $stub = $this->files->get($this->getKernelStub());
            $this->replaceModule($stub, $module)->replaceClassName($stub, $className);

            $this->files->put($this->module->path('app/Console/Kernel.php'), $stub);
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getKernelStub()
    {
        return __DIR__ . '/../stubs/console_kernel.stub';
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
            $moduleName = $this->module->getBaseNamespace();

            return $rootNamespace . "\\$moduleName\App\Console\Commands";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
