<?php

namespace Bchalier\Modules;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use SystemModules\Core\App\Models\Module;

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
            $this->loadFactories($module);
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
        foreach ($module->aliases as $abstract => $alias)
            $this->app->alias($abstract, $alias);
    }

    /**
     * Load module commands.
     *
     * @param Module $module
     */
    protected function loadConsole(Module $module)
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

    /**
     * Load module factories.
     *
     * @param Module $module
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function loadFactories(Module $module)
    {
        if (class_exists('Faker\Factory')) {
            $this->app->make('Illuminate\Database\Eloquent\Factory')->load($module->path . '/database/factories');
        }
    }

    /**
     * Load module migrations unless said in the module composer.json config.
     *
     * @param Module $module
     */
    protected function loadMigrations(Module $module)
    {
        if (empty($module->loadParameters['compartmentalize']['migrations']))
            $this->loadMigrationsFrom($module->path . 'database/migrations');
    }

    /**
     * Load module providers as in the module's composer.json config.
     *
     * @param Module $module
     */
    protected function loadProviders(Module $module)
    {
        foreach ($module->providers as $provider)
            $this->app->register($provider);
    }

    /**
     *  Load module routes.
     *
     * @param Module $module
     */
    protected function loadRoutes(Module $module)
    {
        if ($module->isSystem())
            $namespace = 'SystemModules\\' . $module->name . '\App\Http\Controllers';
        else
            $namespace = 'Modules\\' . $module->name . '\App\Http\Controllers';

        // API routes
        Route::prefix(config('routing.prefix.api'))
            ->middleware('api')
            ->namespace($namespace)
            ->group(base_path($module->path . 'routes/api.php'));

        // web routes
        Route::prefix(config('routing.prefix.web'))
            ->middleware('web')
            ->namespace($namespace)
            ->group(base_path($module->path . 'routes/web.php'));

        // channels routes
        $this->loadRoutesFrom(base_path($module->path . 'routes/channels.php'));

        // console routes
        $this->loadRoutesFrom(base_path($module->path . 'routes/console.php'));
    }

    /**
     * Load module translations.
     *
     * @param Module $module
     */
    protected function loadTranslations(Module $module)
    {
        $this->loadTranslationsFrom(base_path($module->path . 'resources/lang'), $module->alias);
    }

    /**
     * Load module views.
     *
     * @param Module $module
     */
    protected function loadViews(Module $module)
    {
        $this->loadViewsFrom(base_path($module->path . 'resources/views'), $module->alias);
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
                    $configFile->getRealPath(), $configFile->getFilenameWithoutExtension()
                );
            }
        }
    }
}
