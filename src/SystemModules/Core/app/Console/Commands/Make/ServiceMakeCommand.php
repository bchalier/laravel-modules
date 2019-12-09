<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use SystemModules\Core\Console\Commands\traits\ExtendMakeCommand;

class ServiceMakeCommand extends GeneratorCommand
{
    use ExtendMakeCommand;
    
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';
    
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/service.stub';
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
            
            return $rootNamespace . "\\$moduleName\App\Services";
        } else {
            return parent::getDefaultNamespace($rootNamespace);
        }
    }
}
