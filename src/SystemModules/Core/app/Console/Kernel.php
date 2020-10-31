<?php

namespace Bchalier\SystemModules\Core\App\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class Kernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    public $commands = [
        Commands\Make\ModuleMakeCommand::class,
        Commands\Module\ModuleEnable::class,
        Commands\Module\ModuleDisable::class,
        Commands\Module\ModuleDelete::class,
        Commands\Module\ModuleUninstall::class,
        Commands\Module\ModuleInstall::class,
        Commands\Module\ModuleReinstall::class,
        Commands\Make\ServiceMakeCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
//        $schedule->command('inspire')->everyMinute();
    }
}
