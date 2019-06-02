<?php

namespace SystemModules\Core\App\Console\Commands;

use Illuminate\Console\Command;
use SystemModules\Core\App\Facades\ModulesManager;
use SystemModules\Core\App\Models\Module;

class ModuleInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:install {modules*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable a module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->argument('modules') as $moduleName) {
            $module = Module::findAlias($moduleName);

            if ($module) {
                $this->error("Module $moduleName is already installed.");
                continue;
            }

            $moduleName = ucfirst($moduleName);
            $relativePath = "modules/$moduleName/";

            if (ModulesManager::install($relativePath)) {
                $this->info("Module $moduleName uninstalled successfully!");
            } else {
                $this->error("An error occurred while uninstalling the module $moduleName.");
            }
        }
    }
}
