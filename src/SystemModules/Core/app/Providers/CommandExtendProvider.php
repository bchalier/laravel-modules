<?php

namespace Bchalier\SystemModules\Core\App\Providers;

use Bchalier\SystemModules\Core\App\Console\Commands\Database\SeedCommand;
use Bchalier\SystemModules\Core\App\Console\Commands\Make;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class CommandExtendProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        SeedCommand::class                  => 'command.seed',
        Make\ChannelMakeCommand::class      => 'command.channel.make',
        Make\ConsoleMakeCommand::class      => 'command.console.make',
        Make\ControllerMakeCommand::class   => 'command.controller.make',
        Make\EventMakeCommand::class        => 'command.event.make',
        Make\ExceptionMakeCommand::class    => 'command.exception.make',
        Make\FactoryMakeCommand::class      => 'command.factory.make',
        Make\JobMakeCommand::class          => 'command.job.make',
        Make\ListenerMakeCommand::class     => 'command.listener.make',
        Make\MailMakeCommand::class         => 'command.mail.make',
        Make\MiddlewareMakeCommand::class   => 'command.middleware.make',
        Make\MigrateMakeCommand::class      => 'command.migrate.make',
        Make\ModelMakeCommand::class        => 'command.model.make',
        Make\NotificationMakeCommand::class => 'command.notification.make',
        Make\ObserverMakeCommand::class     => 'command.observer.make',
        Make\PolicyMakeCommand::class       => 'command.policy.make',
        Make\ProviderMakeCommand::class     => 'command.provider.make',
        Make\RequestMakeCommand::class      => 'command.request.make',
        Make\ResourceMakeCommand::class     => 'command.resource.make',
        Make\RuleMakeCommand::class         => 'command.rule.make',
        Make\SeederMakeCommand::class       => 'command.seeder.make',
        Make\TestMakeCommand::class         => 'command.test.make',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->commands as $class => $name) {
            $method = "register" . Str::afterLast($class, '\\');

            if (method_exists($this, $method)) {
                $this->$method($name);
            } else {
                $this->app->extend($name, function () use ($class) {
                    return new $class($this->app['files']);
                });
            }
        }

        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return $this->commands;
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand(string $name)
    {
        $this->app->extend($name, function () {
            return new Make\MigrateMakeCommand(
                $this->app['migration.creator'],
                $this->app['composer']
            );
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSeederMakeCommand(string $name)
    {
        $this->app->extend($name, function () {
            return new Make\SeederMakeCommand(
                $this->app['files'],
                $this->app['composer']
            );
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSeedCommand(string $name)
    {
        $this->app->extend($name, function () {
            return new SeedCommand(
                $this->app['db']
            );
        });
    }
}
