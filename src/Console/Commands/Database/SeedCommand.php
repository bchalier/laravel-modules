<?php

namespace Bchalier\LaravelModules\App\Console\Commands\Database;

use Bchalier\SystemModules\Core\App\Models\Module;
use Database\Seeders\DatabaseSeeder;
use Facades\ModulesManager;
use Illuminate\Database\Console\Seeds\SeedCommand as BaseSeedCommand;

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
