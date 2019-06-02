<?php

namespace SystemModules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

use SystemModules\Core\App\Console\Commands\MakeCommand;
use SystemModules\Core\App\Console\Commands\MakeController;
use SystemModules\Core\App\Console\Commands\MakeEvent;
use SystemModules\Core\App\Console\Commands\MakeException;
use SystemModules\Core\App\Console\Commands\MakeFactory;
use SystemModules\Core\App\Console\Commands\MakeJob;
use SystemModules\Core\App\Console\Commands\MakeListener;
use SystemModules\Core\App\Console\Commands\MakeMail;
use SystemModules\Core\App\Console\Commands\MakeMiddleware;
use SystemModules\Core\App\Console\Commands\MakeMigration;
use SystemModules\Core\App\Console\Commands\MakeModel;
use SystemModules\Core\App\Console\Commands\MakeNotification;
use SystemModules\Core\App\Console\Commands\MakeRequest;
use SystemModules\Core\App\Console\Commands\MakeResource;
use SystemModules\Core\App\Console\Commands\MakeSeeder;

class OverrideCommandProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->extend('command.console.make', function () {
            return new MakeCommand(new Filesystem);
        });

        $this->app->extend('command.controller.make', function () {
            return new MakeController(new Filesystem);
        });

        $this->app->extend('command.event.make', function () {
            return new MakeEvent(new Filesystem);
        });

        $this->app->extend('command.exception.make', function () {
            return new MakeException(new Filesystem);
        });

        $this->app->extend('command.factory.make', function () {
            return new MakeFactory(new Filesystem);
        });

        $this->app->extend('command.job.make', function () {
            return new MakeJob(new Filesystem);
        });

        $this->app->extend('command.listener.make', function () {
            return new MakeListener(new Filesystem);
        });

        $this->app->extend('command.model.make', function () {
            return new MakeModel(new Filesystem);
        });

        $this->app->extend('command.mail.make', function () {
            return new MakeMail(new Filesystem);
        });

        $this->app->extend('command.middleware.make', function () {
            return new MakeMiddleware(new Filesystem);
        });

        $this->app->extend('command.notification.make', function () {
            return new MakeNotification(new Filesystem);
        });

        $this->app->extend('command.request.make', function () {
            return new MakeRequest(new Filesystem);
        });

        $this->app->extend('command.resource.make', function () {
            return new MakeResource(new Filesystem);
        });

        $this->app->extend('command.seeder.make', function () {
            return new MakeSeeder(new Filesystem, $this->app->get('composer'));
        });

        $this->app->extend('command.migrate.make', function () {
            return new MakeMigration($this->app->get('migration.creator'), $this->app->get('composer'));
        });
    }
}
