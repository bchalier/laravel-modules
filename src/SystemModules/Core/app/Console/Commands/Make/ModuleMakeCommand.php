<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use SystemModules\Core\App\Facades\ModulesManager;
use SystemModules\Core\App\Models\Module;

class ModuleMakeCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /** @var array : list the available commands for the --fill option */
    const fillMakeCommandList = [ // the commented items are not yet supported
//        'channel',
        'command',
        'controller',
        'event',
        'exception',
        'factory',
        'job',
        'listener',
        'mail',
        'middleware',
        'migration',
        'model',
        'notification',
//        'observer',
//        'policy',
//        'provider',
        'request',
        'resource',
//        'rule',
        'seeder',
        'service',
//        'test',
    ];

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        
        $this->files = $files;
    }
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:module';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module';
    
    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        foreach ($this->argument('modules') as $module) {
            $module = ucfirst(strtolower($module));
            
            if (!$this->generate($module))
                continue;
            
            $this->info("Module $module generated successfully!");
        }
    }
    
    /**
     * Generate base module structure and config.
     *
     * @param string $module
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function generate($module)
    {
        $relativePath = "modules/$module/";
        $path = base_path($relativePath);
        
        $fileExist = $this->files->exists($path);
        $installed = !empty(Module::findAlias($module));
        
        if ($fileExist && $installed) {
            $this->error("Module $module is already installed.");
            return false;
        } elseif ($fileExist) {
            $this->error("Module $module's files exist but not installed.");
            return false;
        } elseif ($installed) {
            $this->error("Module $module is installed but files doesn't exist.");
            return false;
        }
        
        $this->makeDirectory($path);
        $this->makeDefaultConfig($module, $path);
        
        ModulesManager::install($relativePath, true);
        
        $this->makeRoutes($path);
        $this->makeDatabaseDirectory($module, $path);

        if ($this->option('fill')) {
            $this->fill($module);
        }

        return true;
    }
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getConfigStub()
    {
        return __DIR__ . '/stubs/module_config.stub';
    }
    
    /**
     * Get the stub file for the generator.
     *
     * @return array
     */
    protected function getRoutesStub()
    {
        return [
            'api' => __DIR__ . '/stubs/route_api.stub',
            'channels' => __DIR__ . '/stubs/route_channels.stub',
            'console' => __DIR__ . '/stubs/route_console.stub',
            'web' => __DIR__ . '/stubs/route_web.stub',
        ];
    }
    
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getSeederStub()
    {
        return __DIR__ . '/stubs/database_seeder.stub';
    }
    
    /**
     * Replace the name for the given stub.
     *
     * @param  string $stub
     * @param  string $name
     * @return $this
     */
    protected function replaceName(&$stub, $name)
    {
        $stub = str_replace('DummyName', $name, $stub);
        
        return $this;
    }
    
    /**
     * Replace the name for the given stub.
     *
     * @param  string $stub
     * @param  string $alias
     * @return $this
     */
    protected function replaceAlias(&$stub, $alias)
    {
        $stub = str_replace('DummyAlias', $alias, $stub);
        
        return $this;
    }
    
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['modules', InputArgument::IS_ARRAY, 'The list of modules to create'],
        ];
    }
    
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['fill', null, InputOption::VALUE_NONE, 'Fill the module with example stuff'],
        ];
    }
    
    /**
     * Create the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true, true);
        }
        
        return $path;
    }
    
    /**
     * Create the default config file for the module.
     *
     * @param $module
     * @param $path
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeDefaultConfig($module, $path)
    {
        $stub = $this->files->get($this->getConfigStub());
        $this->replaceAlias($stub, strtolower($module))->replaceName($stub, $module);
        
        $this->files->put($path . '/composer.json', $stub);
    }
    
    /**
     * Create the the routes files.
     *
     * @param $path
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeRoutes($path)
    {
        $this->makeDirectory($path . 'routes');
        $stubsPath = $this->getRoutesStub();
        
        foreach ($stubsPath as $route => $stubPath) {
            $stub = $this->files->get($stubPath);
            $this->files->put($path . "routes/$route.php", $stub);
        }
    }
    
    /**
     * Create the database directory.
     *
     * @param $module
     * @param $path
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeDatabaseDirectory($module, $path)
    {
        $path = $path . 'database/';
        
        $this->makeDirectory($path . 'factories');
        $this->makeDirectory($path . 'migrations');
        $this->makeDirectory($path . 'seeds');
    
        $stub = $this->files->get($this->getSeederStub());
        $this->replaceName($stub, $module);
    
        $this->files->put($path . 'seeds/DatabaseSeeder.php', $stub);
    }

    /**
     * Call all the make:* commands for the current module
     *
     * @param $module
     */
    protected function fill($module)
    {
        $bar = $this->output->createProgressBar(count(self::fillMakeCommandList));
        $bar->start();

        foreach (self::fillMakeCommandList as $command) {
            $this->callSilent("make:$command", [
                '--module' => $module,
                'name' => 'Dummy' . ucfirst($command)
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->line(''); // the next line will be next to the progress bar if we don't do that
    }
}
