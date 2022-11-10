<?php

namespace Bchalier\LaravelModules\App\Abstracts;

use Bchalier\LaravelModules\App\Contracts\DeclareBaseNamespace;
use Bchalier\LaravelModules\App\Models\Module;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

abstract class AbstractLaravelModulesServiceProvider extends ServiceProvider implements DeclareBaseNamespace
{
    /**
     * @param Collection $modules
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
//        $this->loadConfig();
//        $this->loadProviders();
//        $this->loadAliases();
//        $this->loadMigrations();
        $this->loadConsole();
//        $this->loadRoutes();
//        $this->loadTranslations();
//        $this->loadViews();
    }

    /**
     * Load aliases by module.
     *
     * @param Module $module
     */
    protected function loadAliases()
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
    protected function loadConsole()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $kernelClass = "{$this->rootNamespace()}\App\Console\Kernel";
        if (!class_exists($kernelClass)) {
            return;
        }

        $this
            ->app
            ->make($kernelClass)
            ->boot($this->app);
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
            $routerApi = Route::prefix(config('modules.prefix.api'))
            ->middleware('api')
            ->name($module->getAlias() . 'Providers');

            if (config('modules.prefix_route_namespace') === true) {
                $routerApi->namespace($namespace);
            }

            $routerApi->group($module->path('routes/api.php'));
        }

        // web routes
        if (file_exists($module->path('routes/web.php'))) {
            $routerWeb = Route::prefix(config('modules.prefix.web'))
            ->middleware('web')
            ->name($module->getAlias() . 'Providers');

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

                $this->publishes([
                    $configFile->getRealPath() => config_path($configFile->getFilename()),
                ], 'modules');
            }
        }
    }
}
