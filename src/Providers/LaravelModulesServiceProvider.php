<?php

namespace Bchalier\LaravelModules\App\Providers;

use Bchalier\LaravelModules\App\Abstracts\AbstractLaravelModulesServiceProvider;

class LaravelModulesServiceProvider extends AbstractLaravelModulesServiceProvider
{
    public function rootNamespace(): string
    {
        return 'Bchalier\LaravelModules';
    }

    protected function publishConfig(): void
    {
        $this->publishes([
            __DIR__.'/../../config/modules.php' => config_path('modules.php'),
        ], 'modules-config');
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/modules.php', 'modules');
    }
}
