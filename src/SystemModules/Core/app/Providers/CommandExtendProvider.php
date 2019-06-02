<?php

namespace SystemModules\Core\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

use SystemModules\Core\App\Console\Commands\Make\ConsoleMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\ControllerMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\EventMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\ExceptionMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\FactoryMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\JobMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\ListenerMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\MailMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\MiddlewareMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\MigrateMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\ModelMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\NotificationMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\RequestMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\ResourceMakeCommand;
use SystemModules\Core\App\Console\Commands\Make\SeederMakeCommand;


class CommandExtendProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
//        'Seed' => 'command.seed',
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $devCommands = [
//        'ChannelMake' => 'command.channel.make',
        'ConsoleMake' => 'command.console.make',
        'ControllerMake' => 'command.controller.make',
        'EventMake' => 'command.event.make',
        'ExceptionMake' => 'command.exception.make',
        'FactoryMake' => 'command.factory.make',
        'JobMake' => 'command.job.make',
        'ListenerMake' => 'command.listener.make',
        'MailMake' => 'command.mail.make',
        'MiddlewareMake' => 'command.middleware.make',
        'MigrateMake' => 'command.migrate.make',
        'ModelMake' => 'command.model.make',
        'NotificationMake' => 'command.notification.make',
//        'ObserverMake' => 'command.observer.make',
//        'PolicyMake' => 'command.policy.make',
//        'ProviderMake' => 'command.provider.make',
        'RequestMake' => 'command.request.make',
        'ResourceMake' => 'command.resource.make',
//        'RuleMake' => 'command.rule.make',
        'SeederMake' => 'command.seeder.make',
//        'TestMake' => 'command.test.make',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands(array_merge(
            $this->commands, $this->devCommands
        ));
    }

    /**
     * Register the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerChannelMakeCommand()
    {
        $this->app->extend('command.channel.make', function () {
            return new ChannelMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerConsoleMakeCommand()
    {
        $this->app->extend('command.console.make', function () {
            return new ConsoleMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerControllerMakeCommand()
    {
        $this->app->extend('command.controller.make', function () {
            return new ControllerMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerEventMakeCommand()
    {
        $this->app->extend('command.event.make', function () {
            return new EventMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerExceptionMakeCommand()
    {
        $this->app->extend('command.exception.make', function () {
            return new ExceptionMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerFactoryMakeCommand()
    {
        $this->app->extend('command.factory.make', function () {
            return new FactoryMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerJobMakeCommand()
    {
        $this->app->extend('command.job.make', function () {
            return new JobMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerListenerMakeCommand()
    {
        $this->app->extend('command.listener.make', function () {
            return new ListenerMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMailMakeCommand()
    {
        $this->app->extend('command.mail.make', function () {
            return new MailMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMiddlewareMakeCommand()
    {
        $this->app->extend('command.middleware.make', function () {
            return new MiddlewareMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerMigrateMakeCommand()
    {
        $this->app->extend('command.migrate.make', function () {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = app('migration.creator');

            $composer = app('composer');

            return new MigrateMakeCommand($creator, $composer);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerModelMakeCommand()
    {
        $this->app->extend('command.model.make', function () {
            return new ModelMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerNotificationMakeCommand()
    {
        $this->app->extend('command.notification.make', function () {
            return new NotificationMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerObserverMakeCommand()
    {
        $this->app->extend('command.observer.make', function () {
            return new ObserverMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerPolicyMakeCommand()
    {
        $this->app->extend('command.policy.make', function () {
            return new PolicyMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerProviderMakeCommand()
    {
        $this->app->extend('command.provider.make', function () {
            return new ProviderMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRequestMakeCommand()
    {
        $this->app->extend('command.request.make', function () {
            return new RequestMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerResourceMakeCommand()
    {
        $this->app->extend('command.resource.make', function () {
            return new ResourceMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRuleMakeCommand()
    {
        $this->app->extend('command.rule.make', function () {
            return new RuleMakeCommand(app('files'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSeederMakeCommand()
    {
        $this->app->extend('command.seeder.make', function () {
            return new SeederMakeCommand(app('files'), app('composer'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerSeedCommand()
    {
        $this->app->extend($this->commands['Seed'], function () {
            return new SeedCommand(app('db'));
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerTestMakeCommand()
    {
        $this->app->extend('command.test.make', function () {
            return new TestMakeCommand(app('files'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(array_values($this->commands), array_values($this->devCommands));
    }
}
