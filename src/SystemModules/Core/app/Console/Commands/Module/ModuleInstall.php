<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Module;

use Illuminate\Console\Command;
use Bchalier\SystemModules\Core\App\Facades\ModulesManager;
use Bchalier\SystemModules\Core\App\Models\Module;

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

            $relativePath = "modules/$moduleName/";

            if (! ModulesManager::install($relativePath)) {
                $this->error("An error occurred while uninstalling the module $moduleName.");
                return 1;
            }

            $this->info("Module $moduleName installed successfully!");
        }
    }
}
