<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Module;

use Illuminate\Console\Command;
use Bchalier\SystemModules\Core\App\Models\Module;

class ModuleEnable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:enable {modules*}';

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
        foreach ($this->argument('modules') as $module) {
            $module = Module::findAlias($module);

            if (!$module) {
                $this->error("Module $module doesn't exist.");
                continue;
            }

            if ($module->isActive()) {
                $this->info("Module {$module->getNameOrAlias()} is already active.");
                continue;
            }

            if ($module->enable()) {
                $this->info("Module {$module->getNameOrAlias()} enabled successfully!");
            } else {
                $this->error("An error occurred for enabling the module {$module->getNameOrAlias()}");
            }
        }
    }
}
