<?php

namespace Bchalier\LaravelModules\App\Console\Commands\Module;

use Bchalier\SystemModules\Core\App\Models\Module;
use Illuminate\Console\Command;

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
    protected $description = 'Delete a module';

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
                $this->error("{$module->getNameOrAlias()} module is a system module and cannot be deleted.");
                continue;
            }

            $message = "Do you really want to delete the {$module->getNameOrAlias()} " .
                "module (both in database and files) ?" .
                "It will be eaten by goblins and cannot ever be recovered.";

            if ($this->confirm($message)) {
                if ($module->delete()) {
                    $this->info("The {$module->getNameOrAlias()} module has been successfully eaten by goblins.");
                } else {
                    $message = "An error occurred while eating the module {$module->getNameOrAlias()}, " .
                        "our goblins are sick now, congratulation.";
                    $this->error($message);
                }
            }
        }
    }
}
