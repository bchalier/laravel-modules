<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Concerns;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Bchalier\SystemModules\Core\App\Models\Module;

trait ExtendMakeCommand
{
    /** @var Module */
    protected $module;
    
    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if (!empty($moduleAlias = $this->option('module'))) {
            if (!$this->setModule($moduleAlias)) {
                $this->error("$moduleAlias module don't exists!");
                return false;
            }
        }

        $return = parent::handle();

        if (method_exists($this, 'afterHandle') && $return !== false) {
            $return = $this->afterHandle();
        }
        
        return $return;
    }

    /**
     * Return the module.
     *
     * @return Module
     */
    protected function getModule()
    {
        return $this->module;
    }

    /**
     * Fetch and store the module from it's alias.
     *
     * @param $moduleAlias
     * @return Module
     */
    protected function setModule($moduleAlias)
    {
        return $this->module = Module::findAlias($moduleAlias);
    }
    
    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        $options = parent::getOptions();
        
        $options[] = ['module', 'M', InputOption::VALUE_OPTIONAL, 'The module where the command should be created.'];
        
        return $options;
    }
    
    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        if (!empty($this->option('module'))) {
            $name = Str::replaceFirst($this->rootNamespace(), strtolower($this->rootNamespace()), $name);

            return base_path().'/'.str_replace('\\', '/', lcfirst($name)).'.php';
        } else {
            return parent::getPath($name);
        }
    }
    
    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function moduleRootNamespace()
    {
        return 'Modules';
    }
    
    /**
     * Replace the module for the given stub.
     *
     * @param  string  $stub
     * @param  string  $module
     * @return $this
     */
    protected function replaceModule(&$stub, $module)
    {
        $stub = str_replace('DummyModule', $module, $stub);
        
        return $this;
    }
    
    /**
     * Replace the ClassName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceClassName(&$stub, $name)
    {
        $stub = str_replace('DummyClass', $name, $stub);
        
        return $this;
    }
    
    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param $name
     * @return mixed|string
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');
        
        if ($this->option('module'))
            $rootNamespace = $this->moduleRootNamespace();
        else
            $rootNamespace = $this->rootNamespace();
        
        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }
        
        $name = str_replace('/', '\\', $name);
        
        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }
}
