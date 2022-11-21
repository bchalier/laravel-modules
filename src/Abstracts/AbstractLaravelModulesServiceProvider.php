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
    public function rootPath(string $path = ''): string
    {
        return lcfirst(str_replace(
                '\\',
                '/',
            $this->rootNamespace(),
        )) . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * @param Collection $modules
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadConfig();
        $this->loadMigrations();
        $this->loadConsole();
        $this->loadRoutes();
//        $this->loadTranslations();
//        $this->loadViews();
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
    protected function loadMigrations()
    {
        if (empty($module->loadParameters['compartmentalize']['migrations'])) {
            $this->loadMigrationsFrom($this->rootPath('database/migrations'));
        }
    }

    /**
     *  Load module routes.
     */
    protected function loadRoutes()
    {
        $namespace = $this->rootNamespace() . '\App\Http\Controllers';

        // API routes
        if (file_exists($this->rootPath('routes/api.php'))) {
            $routerApi = Route::prefix(config('modules.prefix.api'))
                ->middleware('api');

            if (config('modules.prefix_route_namespace') === true) {
                $routerApi->namespace($namespace);
            }

            $routerApi->group($this->rootPath('routes/api.php'));
        }

        // web routes
        if (file_exists($this->rootPath('routes/web.php'))) {
            $routerWeb = Route::prefix(config('modules.prefix.web'))
                ->middleware('web');

            if (config('modules.prefix_route_namespace') === true) {
                $routerWeb->namespace($namespace);
            }

            $routerWeb->group($this->rootPath('routes/web.php'));
        }

        // channels routes
        if (file_exists($this->rootPath('routes/channels.php'))) {
            $this->loadRoutesFrom($this->rootPath('routes/channels.php'));
        }

        // console routes
        if (file_exists($this->rootPath('routes/console.php'))) {
            $this->loadRoutesFrom($this->rootPath('routes/console.php'));
        }
    }

    /**
     * Load module translations.
     *
     * @param Module $module
     */
    protected function loadTranslations(Module $module)
    {
        $this->loadTranslationsFrom($this->rootPath('resources/lang'), $module->alias);
    }

    /**
     * Load module views.
     *
     * @param Module $module
     */
    protected function loadViews(Module $module)
    {
        $this->loadViewsFrom($this->rootPath('resources/views'), $module->alias);
    }

    /**
     * Load all config files if config directory
     *
     * @param Module $module
     */
    protected function loadConfig()
    {
        if ($this->app['files']->isDirectory($this->rootPath('config'))) {
            /** @var \Symfony\Component\Finder\SplFileInfo $configFile */
            foreach ($this->app['files']->files($this->rootPath('config')) as $configFile) {
                $this->mergeConfigFrom(
                    $configFile->getRealPath(),
                    $configFile->getFilenameWithoutExtension(),
                );

                $this->publishes([
                    $configFile->getRealPath() => config_path($configFile->getFilename()),
                ]);
            }
        }
    }
}
