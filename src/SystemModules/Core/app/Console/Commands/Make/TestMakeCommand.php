<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\TestMakeCommand as BaseTestMakeCommand;
use Illuminate\Support\Str;
use Bchalier\SystemModules\Core\App\Console\Commands\Concerns\ExtendMakeCommand;

class TestMakeCommand extends BaseTestMakeCommand
{
    use ExtendMakeCommand;

    /**
     * Create a new test command instance.
     *
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->signature .= " {--M|module= : The module where the command should be created.}";

        parent::__construct($files);
    }

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

            return base_path().'/'.str_replace('\\', '/', $name).'.php';
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
                return $rootNamespace.'\Unit';
            } else {
                return $rootNamespace.'\Feature';
            }
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
