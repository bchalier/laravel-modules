<?php

namespace SystemModules\Core\Console\Commands\traits;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use SystemModules\Core\App\Models\Module;

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
            $this->module = Module::findAlias($moduleAlias);
            
            if (!$this->module) {
                $this->error("$moduleAlias module don't exists!");
                return false;
            }
        }
        
        $return = parent::handle();
        
        if (method_exists($this, 'postHandle')) {
            $this->postHandle();
        }
        
        return $return;
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
            return $this->laravel->basePath().'/'.str_replace('\\', '/', lcfirst($name)).'.php';
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
