<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Database;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Console\Seeds\SeedCommand as BaseSeedCommand;
use Bchalier\SystemModules\Core\App\Facades\ModulesManager;
use Bchalier\SystemModules\Core\App\Models\Module;

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

        if ($this->option('class') == DatabaseSeeder::class) {
            /** @var Module $module */
            foreach (ModulesManager::getActiveModules() as $module) {
                $class = "Modules\\{$module->getBaseNamespace()}\Database\Seeders\DatabaseSeeder";

                $this->call('db:seed', [
                    '--class' => $class
                ]);
            }
        }
    }
}
