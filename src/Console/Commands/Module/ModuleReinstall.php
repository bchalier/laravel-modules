<?php

namespace Bchalier\LaravelModules\App\Console\Commands\Module;

use Bchalier\SystemModules\Core\App\Models\Module;
use Illuminate\Console\Command;

class ModuleReinstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:reinstall {modules*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reinstall a module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $modules = $this->argument('modules');

        $this->call('module:uninstall', ['modules' => $modules]);
        $this->call('module:install', ['modules' => $modules]);
    }
}
