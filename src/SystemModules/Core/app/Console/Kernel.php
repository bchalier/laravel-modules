<?php

namespace SystemModules\Core\App\Console;

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
        Commands\ModuleEnable::class,
        Commands\ModuleDisable::class,
        Commands\ModuleDelete::class,
        Commands\ModuleUninstall::class,
        Commands\ModuleInstall::class,
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
