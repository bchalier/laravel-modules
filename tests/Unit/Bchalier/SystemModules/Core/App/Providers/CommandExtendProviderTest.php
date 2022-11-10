<?php

namespace Tests\Unit\Bchalier\SystemModules\Core\Providers;

use Bchalier\LaravelModules\Console\Commands\Database\SeedCommand;
use Bchalier\LaravelModules\Providers\ArtisanExtendServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;

class CommandExtendProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ArtisanExtendServiceProvider::class];
    }

    public function testProvides()
    {
        $commands = [
            SeedCommand::class                  => 'command.seed',
            \Bchalier\LaravelModules\Console\Commands\Make\ChannelMakeCommand::class      => 'command.channel.make',
            \Bchalier\LaravelModules\Console\Commands\Make\ConsoleMakeCommand::class      => 'command.console.make',
            \Bchalier\LaravelModules\Console\Commands\Make\ControllerMakeCommand::class   => 'command.controller.make',
            \Bchalier\LaravelModules\Console\Commands\Make\EventMakeCommand::class        => 'command.event.make',
            \Bchalier\LaravelModules\Console\Commands\Make\ExceptionMakeCommand::class    => 'command.exception.make',
            \Bchalier\LaravelModules\Console\Commands\Make\FactoryMakeCommand::class      => 'command.factory.make',
            \Bchalier\LaravelModules\Console\Commands\Make\JobMakeCommand::class          => 'command.job.make',
            \Bchalier\LaravelModules\Console\Commands\Make\ListenerMakeCommand::class     => 'command.listener.make',
            \Bchalier\LaravelModules\Console\Commands\Make\MailMakeCommand::class         => 'command.mail.make',
            \Bchalier\LaravelModules\Console\Commands\Make\MiddlewareMakeCommand::class   => 'command.middleware.make',
            \Bchalier\LaravelModules\Console\Commands\Make\MigrateMakeCommand::class      => 'command.migrate.make',
            \Bchalier\LaravelModules\Console\Commands\Make\ModelMakeCommand::class        => 'command.model.make',
            \Bchalier\LaravelModules\Console\Commands\Make\NotificationMakeCommand::class => 'command.notification.make',
            \Bchalier\LaravelModules\Console\Commands\Make\ObserverMakeCommand::class     => 'command.observer.make',
            \Bchalier\LaravelModules\Console\Commands\Make\PolicyMakeCommand::class       => 'command.policy.make',
            \Bchalier\LaravelModules\Console\Commands\Make\ProviderMakeCommand::class     => 'command.provider.make',
            \Bchalier\LaravelModules\Console\Commands\Make\RequestMakeCommand::class      => 'command.request.make',
            \Bchalier\LaravelModules\Console\Commands\Make\ResourceMakeCommand::class     => 'command.resource.make',
            \Bchalier\LaravelModules\Console\Commands\Make\RuleMakeCommand::class         => 'command.rule.make',
            \Bchalier\LaravelModules\Console\Commands\Make\SeederMakeCommand::class       => 'command.seeder.make',
            \Bchalier\LaravelModules\Console\Commands\Make\TestMakeCommand::class         => 'command.test.make',
        ];

        $this->assertEquals(
            $commands,
            (new ArtisanExtendServiceProvider($this->app))->provides()
        );
    }

    /**
     * @depends testProvides
     * @dataProvider registerDataProvider
     */
    public function testRegister(string $name)
    {
        $exitCode = Artisan::call($name, ['--help' => true]);

        $this->assertEquals(0, $exitCode);
    }

    public function registerDataProvider()
    {
        return [
            'seed' => [
                'name' => "db:seed"
            ],

            'channel' => [
                'name' => "make:channel"
            ],

            'console' => [
                'name' => "make:command"
            ],

            'controller' => [
                'name' => "make:controller"
            ],

            'event' => [
                'name' => "make:event"
            ],

            'exception' => [
                'name' => "make:exception"
            ],

            'factory' => [
                'name' => "make:factory"
            ],

            'job' => [
                'name' => "make:job"
            ],

            'listener' => [
                'name' => "make:listener"
            ],

            'mail' => [
                'name' => "make:mail"
            ],

            'middleware' => [
                'name' => "make:middleware"
            ],

            'migrate' => [
                'name' => "make:migration"
            ],

            'model' => [
                'name' => "make:model"
            ],

            'notification' => [
                'name' => "make:notification"
            ],

            'observer' => [
                'name' => "make:observer"
            ],

            'policy' => [
                'name' => "make:policy"
            ],

            'provider' => [
                'name' => "make:provider"
            ],

            'request' => [
                'name' => "make:request"
            ],

            'resource' => [
                'name' => "make:resource"
            ],

            'rule' => [
                'name' => "make:rule"
            ],

            'seeder' => [
                'name' => "make:seeder"
            ],

            'test' => [
                'name' => "make:test"
            ],
        ];
    }
}
