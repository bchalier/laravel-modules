<?php

namespace Bchalier\LaravelModules\App\Providers;

use Bchalier\LaravelModules\App\Console\Commands\Database\SeedCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ChannelMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ConsoleMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ControllerMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\EventMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ExceptionMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\FactoryMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\JobMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ListenerMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\MailMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\MiddlewareMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ModelMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\NotificationMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ObserverMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\PolicyMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ProviderMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\RequestMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\ResourceMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\RuleMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\SeederMakeCommand;
use Bchalier\LaravelModules\App\Console\Commands\Make\TestMakeCommand;
use Illuminate\Foundation\Providers\ArtisanServiceProvider;

class ArtisanExtendServiceProvider extends ArtisanServiceProvider
{
    protected function registerSeedCommand(): void
    {
        $this->app->singleton('command.seed', function ($app) {
            return new SeedCommand($app['db']);
        });
    }

    protected function registerChannelMakeCommand(): void
    {
        $this->app->singleton('command.channel.make', function ($app) {
            return new ChannelMakeCommand($app['files']);
        });
    }

    protected function registerConsoleMakeCommand(): void
    {
        $this->app->singleton('command.console.make', function ($app) {
            return new ConsoleMakeCommand($app['files']);
        });
    }

    protected function registerControllerMakeCommand(): void
    {
        $this->app->singleton('command.controller.make', function ($app) {
            return new ControllerMakeCommand($app['files']);
        });
    }

    protected function registerEventMakeCommand(): void
    {
        $this->app->singleton('command.event.make', function ($app) {
            return new EventMakeCommand($app['files']);
        });
    }

    protected function registerExceptionMakeCommand(): void
    {
        $this->app->singleton('command.exception.make', function ($app) {
            return new ExceptionMakeCommand($app['files']);
        });
    }

    protected function registerFactoryMakeCommand(): void
    {
        $this->app->singleton('command.factory.make', function ($app) {
            return new FactoryMakeCommand($app['files']);
        });
    }

    protected function registerJobMakeCommand(): void
    {
        $this->app->singleton('command.job.make', function ($app) {
            return new JobMakeCommand($app['files']);
        });
    }

    protected function registerListenerMakeCommand(): void
    {
        $this->app->singleton('command.listener.make', function ($app) {
            return new ListenerMakeCommand($app['files']);
        });
    }

    protected function registerMailMakeCommand(): void
    {
        $this->app->singleton('command.mail.make', function ($app) {
            return new MailMakeCommand($app['files']);
        });
    }

    protected function registerMiddlewareMakeCommand(): void
    {
        $this->app->singleton('command.middleware.make', function ($app) {
            return new MiddlewareMakeCommand($app['files']);
        });
    }

    protected function registerModelMakeCommand(): void
    {
        $this->app->singleton('command.model.make', function ($app) {
            return new ModelMakeCommand($app['files']);
        });
    }

    protected function registerNotificationMakeCommand(): void
    {
        $this->app->singleton('command.notification.make', function ($app) {
            return new NotificationMakeCommand($app['files']);
        });
    }

    protected function registerObserverMakeCommand(): void
    {
        $this->app->singleton('command.observer.make', function ($app) {
            return new ObserverMakeCommand($app['files']);
        });
    }

    protected function registerPolicyMakeCommand(): void
    {
        $this->app->singleton('command.policy.make', function ($app) {
            return new PolicyMakeCommand($app['files']);
        });
    }

    protected function registerProviderMakeCommand(): void
    {
        $this->app->singleton('command.provider.make', function ($app) {
            return new ProviderMakeCommand($app['files']);
        });
    }

    protected function registerRequestMakeCommand(): void
    {
        $this->app->singleton('command.request.make', function ($app) {
            return new RequestMakeCommand($app['files']);
        });
    }

    protected function registerResourceMakeCommand(): void
    {
        $this->app->singleton('command.resource.make', function ($app) {
            return new ResourceMakeCommand($app['files']);
        });
    }

    protected function registerRuleMakeCommand(): void
    {
        $this->app->singleton('command.rule.make', function ($app) {
            return new RuleMakeCommand($app['files']);
        });
    }

    protected function registerSeederMakeCommand(): void
    {
        $this->app->singleton('command.seeder.make', function ($app) {
            return new SeederMakeCommand($app['files'], $app['composer']);
        });
    }

    protected function registerTestMakeCommand(): void
    {
        $this->app->singleton('command.test.make', function ($app) {
            return new TestMakeCommand($app['files']);
        });
    }
}
