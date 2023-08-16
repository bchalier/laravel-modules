<?php

namespace Bchalier\LaravelModules\App\Abstracts;

use Bchalier\LaravelModules\App\Concerns\DetectAppNamespace;
use Bchalier\LaravelModules\App\Concerns\DetectAppPath;
use Bchalier\LaravelModules\App\Contracts\CanBoot;
use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class AbstractModulesKernel implements CanBoot
{
    use DetectAppNamespace;
    use DetectAppPath;

    /**
     * The Artisan commands provided by the application.
     *
     * @var array
     */
    protected array $commands = [];

    public function boot(Application $app): void
    {
        Artisan::starting(function ($artisan) {
            $artisan->resolveCommands($this->commands);
        });

        $this->commands();

        $app->booted(function () use ($app) {
            $schedule = $app->make(Schedule::class);
            $this->schedule($schedule);
        });
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        //
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        //
    }

    /**
     * Register all the commands in the given directory.
     *
     * @param  array|string  $paths
     * @return void
     */
    protected function load(array|string $paths): void
    {
        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        foreach ((new Finder)->in($paths)->files() as $command) {
            $commandReference = $this->detectAppNamespace() . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($command->getRealPath(), $this->detectAppPath($command->getRealPath()))
                );

            if (is_subclass_of($commandReference, Command::class) &&
                ! (new \ReflectionClass($commandReference))->isAbstract()) {
                Artisan::starting(function ($artisan) use ($commandReference) {
                    $artisan->resolve($commandReference);
                });
            }
        }
    }
}
