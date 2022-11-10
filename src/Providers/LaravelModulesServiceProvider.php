<?php

namespace Bchalier\LaravelModules\App\Providers;

use Bchalier\LaravelModules\App\Abstracts\AbstractLaravelModulesServiceProvider;

class LaravelModulesServiceProvider extends AbstractLaravelModulesServiceProvider
{
    public function rootNamespace(): string
    {
        return 'Bchalier\LaravelModules';
    }
}
