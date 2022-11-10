<?php

namespace Bchalier\LaravelModules\App\Console\Commands\Make;

use Bchalier\LaravelModules\App\Console\Commands\Concerns\ExtendMakeCommand;
use Illuminate\Foundation\Console\TestMakeCommand as BaseTestMakeCommand;
use Illuminate\Support\Str;

class TestMakeCommand extends BaseTestMakeCommand
{
    use ExtendMakeCommand;

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        if ($this->module) {
            $name = Str::replaceFirst($this->moduleRootNamespace(), strtolower($this->moduleRootNamespace()), $name);
            $name = Str::replaceFirst($this->rootNamespace(), strtolower($this->rootNamespace()), $name);

            return base_path() . 'TestMakeCommand.php/' . str_replace('\\', '/', $name) . '.php';
        } else {
            return parent::getPath($name);
        }
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
            $rootNamespace = $rootNamespace . "\\$moduleName\Tests";

            if ($this->option('unit')) {
                return $rootNamespace . '\Unit';
            } else {
                return $rootNamespace . '\Feature';
            }
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
