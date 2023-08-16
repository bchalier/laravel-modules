<?php

namespace Bchalier\LaravelModules\App\Console;

use Bchalier\LaravelModules\App\Abstracts\AbstractModulesKernel;
use Bchalier\LaravelModules\App\Console\Commands\Make\ModuleMakeCommand;
use Illuminate\Support\Str;

class Kernel extends AbstractModulesKernel
{
    /**
     * The Artisan commands provided by the application.
     *
     * @var array
     */
    protected array $commands = [
        ModuleMakeCommand::class,
    ];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    public function commands(): void
    {
        $this->load([
//            __DIR__ . '/Commands/Module',
//            __DIR__ . '/Commands/Make',
            __DIR__ . '/Commands/Database',
        ]);

        require base_path('routes/console.php');
    }

    protected function detectAppPath(string $path): string
    {
        return Str::before(__DIR__, '/src/') . '/src/';
    }
}
