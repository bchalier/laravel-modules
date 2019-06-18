<?php

namespace SystemModules\Core\App\Console\Commands\Module;

use Illuminate\Console\Command;
use SystemModules\Core\App\Models\Module;

class ModuleDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:delete {modules*}';

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
        foreach ($this->argument('modules') as $moduleAlias) {
            $module = Module::findAlias($moduleAlias);

            if (!$module) {
                $this->error("$moduleAlias module doesn't exist.");
                continue;
            }

            if ($module->isSystem()) {
                $this->error("$module->name module is a system module and cannot be deleted.");
                continue;
            }

            if ($this->confirm("Do you really want to delete the $module->name module (both in database and files) ? It will be eat by goblins and cannot ever be recovered.")) {
                if ($module->delete())
                    $this->info("The $module->name module has been successfully eaten by goblins.");
                else
                    $this->error("An error occurred while eating the module $module->name, ours goblins are sick now, congratulation.");
            }
        }
    }
}
