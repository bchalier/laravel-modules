<?php

namespace Bchalier\Modules;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use SystemModules\Core\App\Facades\ModulesManager;
use SystemModules\Core\App\Models\Module;

class LaravelModulesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $modules = ModulesManager::getActiveModules();

        foreach ($modules as $module) {

            $this->loadMigrations($module);
            $this->loadTranslations($module);
            $this->loadViews($module);
            $this->loadConsole($module);
            $this->loadRoutes($module);
            $this->loadProviders($module);
            $this->loadAliases($module);

        }
    }

    private function loadMigrations(Module $module)
    {
        if (empty($module->loadParameters['compartmentalize']['migrations']))
            $this->loadMigrationsFrom($module->path . 'Database/migrations');
    }

    private function loadViews(Module $module)
    {
        $this->loadViewsFrom(base_path($module->path . 'Resources/views'), $module->alias);
    }

    private function loadTranslations(Module $module)
    {
        $this->loadTranslationsFrom(base_path($module->path . 'Resources/lang'), $module->alias);
    }

    private function loadRoutes(Module $module)
    {
        if ($module->isSystem())
            $namespace = 'SystemModules\\' . $module->name . '\App\Http\Controllers';
        else
            $namespace = 'Modules\\' . $module->name . '\App\Http\Controllers';

        // API routes
        Route::prefix('api')
            ->middleware('api')
            ->namespace($namespace)
            ->group(base_path($module->path . 'routes/api.php'));

        // web routes
        Route::middleware('web')
            ->namespace($namespace)
            ->group(base_path($module->path . 'routes/web.php'));

        // channels routes
        $this->loadRoutesFrom(base_path($module->path . 'routes/channels.php'));

        // console routes
        $this->loadRoutesFrom(base_path($module->path . 'routes/console.php'));
    }

    private function loadConsole(Module $module)
    {
        if ($module->isSystem())
            $kernelClass = 'SystemModules\\' . $module->name . '\App\Console\Kernel';
        else
            $kernelClass = 'Modules\\' . $module->name . '\App\Console\Kernel';

        if (!class_exists($kernelClass))
            return;
        
        $kernel = new $kernelClass;
        $this->commands($kernel->commands);

        $this->app->booted(function () use ($kernel) {
            $schedule = $this->app->make(Schedule::class);
            $kernel->schedule($schedule);
        });
    }

    private function loadProviders(Module $module)
    {
        foreach ($module->providers as $provider)
            $this->app->register($provider);
    }

    private function loadAliases(Module $module)
    {
        foreach ($module->aliases as $abstract => $alias)
            $this->app->alias($abstract, $alias);
    }
}
