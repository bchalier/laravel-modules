<?php

namespace SystemModules\Core\App\Console\Commands\Database;

use Illuminate\Database\Console\Seeds\SeedCommand as BaseSeedCommand;
use SystemModules\Core\App\Facades\ModulesManager;
use SystemModules\Core\App\Models\Module;

class SeedCommand extends BaseSeedCommand
{
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('class') == 'DatabaseSeeder') {
            /** @var Module $module */
            foreach (ModulesManager::getActiveModules() as $module) {
                $class = "Modules\\{$module->getBaseNamespace()}\Database\Seeds\DatabaseSeeder";

                $this->call('db:seed', [
                    '--class' => $class
                ]);
            }
        }
    }
}