<?php

namespace Bchalier\SystemModules\Core\App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Bchalier\SystemModules\Core\App\Models\Module;

class LaravelModulesServiceProvider extends ServiceProvider
{
    /**
     * Load all laravel components for each module.
     */
    public function boot()
    {
        $this->loadAll(Module::system());
        $this->loadAll(Module::where('active', true));
    }

    /**
     * @param Collection $modules
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function loadAll(Collection $modules)
    {
        foreach ($modules as $module) {
            $this->loadConfig($module);
            $this->loadProviders($module);
            $this->loadAliases($module);
            $this->loadMigrations($module);
            $this->loadConsole($module);
            $this->loadRoutes($module);
            $this->loadTranslations($module);
            $this->loadViews($module);
        }
    }

    /**
     * Load aliases by module.
     *
     * @param Module $module
     */
    protected function loadAliases(Module $module)
    {
        foreach ($module->aliases as $abstract => $alias) {
            $this->app->alias($abstract, $alias);
        }
    }

    /**
     * Load module commands.
     *
     * @param Module $module
     */
    protected function loadConsole(Module $module)
    {
        if ($module->isSystem()) {
            $kernelClass = "Bchalier\\SystemModules\\{$module->getBaseNamespace()}\App\Console\Kernel";
        } else {
            $kernelClass = "Modules\\{$module->getBaseNamespace()}\App\Console\Kernel";
        }

        if (!class_exists($kernelClass)) {
            return;
        }

        $kernel = new $kernelClass();
        $this->commands($kernel->commands);

        $this->app->booted(function () use ($kernel) {
            $schedule = $this->app->make(Schedule::class);
            $kernel->schedule($schedule);
        });
    }

    /**
     * Load module migrations unless said in the module composer.json config.
     *
     * @param Module $module
     */
    protected function loadMigrations(Module $module)
    {
        if (empty($module->loadParameters['compartmentalize']['migrations'])) {
            $this->loadMigrationsFrom($module->path('database/migrations'));
        }
    }

    /**
     * Load module providers as in the module's composer.json config.
     *
     * @param Module $module
     */
    protected function loadProviders(Module $module)
    {
        foreach ($module->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     *  Load module routes.
     *
     * @param Module $module
     */
    protected function loadRoutes(Module $module)
    {
        $namespace = "Modules\\{$module->getBaseNamespace()}\App\Http\Controllers";

        if ($module->isSystem()) {
            $namespace = "SystemModules\\{$module->getBaseNamespace()}\App\Http\Controllers";
        }

        // API routes
        if (file_exists($module->path('routes/api.php'))) {
            $routerApi = Route::prefix(config('routing.prefix.api'))
            ->middleware('api')
            ->name($module->getAlias() . '.');

            if (config('modules.prefix_route_namespace') === true) {
                $routerApi->namespace($namespace);
            }

            $routerApi->group($module->path('routes/api.php'));
        }

        // web routes
        if (file_exists($module->path('routes/web.php'))) {
            $routerWeb = Route::prefix(config('routing.prefix.web'))
            ->middleware('web')
            ->name($module->getAlias() . '.');

            if (config('modules.prefix_route_namespace') === true) {
                $routerWeb->namespace($namespace);
            }

            $routerWeb->group($module->path('routes/web.php'));
        }

        // channels routes
        if (file_exists($module->path('routes/channels.php'))) {
            $this->loadRoutesFrom($module->path('routes/channels.php'));
        }

        // console routes
        if (file_exists($module->path('routes/console.php'))) {
            $this->loadRoutesFrom($module->path('routes/console.php'));
        }
    }

    /**
     * Load module translations.
     *
     * @param Module $module
     */
    protected function loadTranslations(Module $module)
    {
        $this->loadTranslationsFrom($module->path('resources/lang'), $module->alias);
    }

    /**
     * Load module views.
     *
     * @param Module $module
     */
    protected function loadViews(Module $module)
    {
        $this->loadViewsFrom($module->path('resources/views'), $module->alias);
    }

    /**
     * Load all config files if config directory
     *
     * @param Module $module
     */
    protected function loadConfig(Module $module)
    {
        if ($this->app['files']->isDirectory($module->path('config'))) {
            /** @var \Symfony\Component\Finder\SplFileInfo $configFile */
            foreach ($this->app['files']->files($module->path('config')) as $configFile) {
                $this->mergeConfigFrom(
                    $configFile->getRealPath(),
                    $configFile->getFilenameWithoutExtension()
                );
            }
        }
    }
}
