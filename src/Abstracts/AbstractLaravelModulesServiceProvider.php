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

    public function absolutePath(string $path = ''): string
    {
        return $this->app->basePath($this->rootPath($path));
    }

    public function alias(): string
    {
        return strtolower(last(explode('\\', $this->rootNamespace())));
    }

    /**
     * @param Collection $modules
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->loadConsole();
            $this->loadMigrations();
        }

        $this->loadRoutes();
        $this->loadTranslations();
        $this->loadViews();
    }

    public function register(): void
    {
        $this->mergeConfig();
    }

    /**
     * Load module commands.
     *
     * @param Module $module
     */
    protected function loadConsole(): void
    {
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
    protected function loadMigrations(): void
    {
        if (empty($module->loadParameters['compartmentalize']['migrations'])) {
            $this->loadMigrationsFrom($this->absolutePath('database/migrations'));
        }
    }

    /**
     *  Load module routes.
     */
    protected function loadRoutes(): void
    {
        $namespace = $this->rootNamespace() . '\App\Http\Controllers';

        // API routes
        if (file_exists($this->absolutePath('routes/api.php'))) {
            $routerApi = Route::prefix(config('modules.prefix.api'))
                ->middleware('api');

            if (config('modules.prefix_route_namespace') === true) {
                $routerApi->namespace($namespace);
            }

            $routerApi->group($this->absolutePath('routes/api.php'));
        }

        // web routes
        if (file_exists($this->absolutePath('routes/web.php'))) {
            $routerWeb = Route::prefix(config('modules.prefix.web'))
                ->middleware('web');

            if (config('modules.prefix_route_namespace') === true) {
                $routerWeb->namespace($namespace);
            }

            $routerWeb->group($this->absolutePath('routes/web.php'));
        }

        // channels routes
        if (file_exists($this->absolutePath('routes/channels.php'))) {
            $this->loadRoutesFrom($this->absolutePath('routes/channels.php'));
        }

        // console routes
        if (file_exists($this->absolutePath('routes/console.php'))) {
            $this->loadRoutesFrom($this->absolutePath('routes/console.php'));
        }
    }

    /**
     * Load module translations.
     *
     * @param Module $module
     */
    protected function loadTranslations(): void
    {
        $this->loadTranslationsFrom($this->absolutePath('lang'), $this->alias());
    }

    /**
     * Load module views.
     *
     * @param Module $module
     */
    protected function loadViews(): void
    {
        $this->loadViewsFrom($this->absolutePath('resources/views'), $this->alias());
    }

    /**
     * Load all config files if config directory
     *
     * @param Module $module
     */
    protected function mergeConfig(): void
    {
        if ($this->app['files']->isDirectory($this->absolutePath('config'))) {
            /** @var \Symfony\Component\Finder\SplFileInfo $configFile */
            foreach ($this->app['files']->files($this->absolutePath('config')) as $configFile) {
                $this->mergeConfigFrom(
                    $configFile->getRealPath(),
                    $configFile->getFilenameWithoutExtension(),
                );
            }
        }
    }

    /**
     * Load all config files if config directory
     *
     * @param Module $module
     */
    protected function publishConfig(): void
    {
        if ($this->app['files']->isDirectory($this->absolutePath('config'))) {
            /** @var \Symfony\Component\Finder\SplFileInfo $configFile */
            foreach ($this->app['files']->files($this->absolutePath('config')) as $configFile) {
                $this->publishes([
                    $configFile->getRealPath() => config_path($configFile->getFilename()),
                ]);
            }
        }
    }
}
