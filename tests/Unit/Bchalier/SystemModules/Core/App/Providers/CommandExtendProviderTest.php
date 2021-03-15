<?php

namespace Tests\Unit\Bchalier\SystemModules\Core\App\Providers;

use Bchalier\SystemModules\Core\App\Console\Commands\Database\SeedCommand;
use Bchalier\SystemModules\Core\App\Console\Commands\Make;
use Bchalier\SystemModules\Core\App\Providers\CommandExtendProvider;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;

class CommandExtendProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [CommandExtendProvider::class];
    }

    public function testProvides()
    {
        $commands = [
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

        $this->assertEquals(
            $commands,
            (new CommandExtendProvider($this->app))->provides()
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
