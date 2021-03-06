<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Module;

use Illuminate\Console\Command;
use Bchalier\SystemModules\Core\App\Models\Module;

class ModuleDisable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable {modules*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a module';

    /**
     * Execute the console command.
     *
     * @return mixed
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
                $this->error("Module {$module->getNameOrAlias()} is a system module and cannot be disabled.");
                continue;
            }

            if (!$module->isActive()) {
                $this->info("Module {$module->getNameOrAlias()} is already disabled.");
                continue;
            }

            if ($module->disable()) {
                $this->info("Module {$module->getNameOrAlias()} disabled successfully!");
            } else {
                $this->error("An error occurred while disabling the module {$module->getNameOrAlias()}");
            }
        }
    }
}
