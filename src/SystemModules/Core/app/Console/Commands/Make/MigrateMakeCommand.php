<?php

namespace SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Database\Console\Migrations\MigrateMakeCommand as BaseMigrateMakeCommand;
use Illuminate\Support\Composer;
use Illuminate\Database\Migrations\MigrationCreator;
use SystemModules\Core\App\Models\Module;

class MigrateMakeCommand extends BaseMigrateMakeCommand
{
    /** @var Module */
    protected $module;
    
    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Database\Migrations\MigrationCreator  $creator
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        $this->signature .= "
                {--M|module= : The module where the command should be created.}
        ";
        
        parent::__construct($creator, $composer);
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!empty($moduleAlias = $this->option('module'))) {
            $this->module = Module::findAlias($moduleAlias);
            
            if (!$this->module) {
                $this->error("$moduleAlias module don't exists!");
                return;
            }
        }
        
        parent::handle();
        
        if (method_exists($this, 'afterHandle')) {
            $this->afterHandle();
        }
    }
    
    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        if ($this->module) {
            if (! is_null($targetPath = $this->input->getOption('path'))) {
                return ! $this->usingRealPath()
                    ? $this->laravel->basePath() . DIRECTORY_SEPARATOR . $targetPath
                    : $targetPath;
            }

            return $this->module->path('database/migrations');
        } else {
            return parent::getMigrationPath();
        }
    }
}
