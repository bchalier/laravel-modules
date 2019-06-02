<?php

namespace SystemModules\Core\App\Console\Commands;

use Illuminate\Console\Command;
use SystemModules\Core\App\Models\Module;

class ModuleUninstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:uninstall {modules*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a module';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        foreach ($this->argument('modules') as $module) {
            $module = Module::findAlias($module);

            if (!$module) {
                $this->error("Module $module doesn't exist.");
                continue;
            }

            if ($module->isSystem()) {
                $this->error("Module $module->name is a system module and cannot be uninstalled.");
                continue;
            }

            if ($this->confirm("Do you really want to uninstall the $module->name module ?")) {
                if ($module->uninstall()) {
                    $this->info("Module $module->name uninstalled successfully!");
                } else {
                    $this->error("An error occurred while uninstalling the module $module->name");
                }
            }
        }
    }
}
