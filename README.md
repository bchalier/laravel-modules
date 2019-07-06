# Divide your laravel application in modules, the laravel way

### Introduction

This package is made to allow you to divide everything laravel allow you to make but divided in modules.

I ended up making this package even if other are doing exactly the same thing because after trying to work with them,
after some time I always found them incomplete, no longer maintained, not respecting laravel standards and ideas (having module:make-controller command instead of make:controller, another file structure etc)
so after trying 3-4 modules I got frustrated and starting making my own package trying to not reproduce the mistakes I saw earlier.

Here are the result, I hove you will like it as much as I do !
This is my first public package so please fell free to send me any suggestions, questions, bug report, feature request you may have !

### Installation

You can install the package via composer:
``` 
composer require bchalier/laravel-modules
```

### Commands

All the standard make commands are extended with a -M|module=[module alias] options, here an example :

``` php artisan make:model -Muser UnicornModel```

As it is an extend and not a replacement all options are available and working (or should be !)

``` php artisan make:model -cfmr -Muser UnicornModel```

The package also add some commands for dealing with the modules themselves :

- ```make:module {modules*} {?--fill}``` : make one or more new module, use the --fill option to add all possible elements in the newly created module.
- ```module:install {modules*}``` : install one or more module (in bdd)
- ```module:reinstall {modules*}``` : reinstall the module (update bdd), useful 
- ```module:uninstall {modules*}``` : uninstall one or more module (keep files, clean bdd)
- ```module:enable {modules*}``` : load the module (the files will always be loader by composer but the providers, routes etc will only if the module is enabled)
- ```module:disable {modules*}``` : disable the module
- ```module:delete {modules*}``` : delete the bdd AND FILES of the module, be careful, this command will delete all your module files and uninstall it.


### Structure

The insides of a module is pretty much just a normal laravel, here an example (make it yourselves with ```php artisan make:module test --fill```) :

```
modules/Test/
├── app
│   ├── Broadcasting
│   │   └── DummyChannel.php
│   ├── Console
│   │   ├── Commands
│   │   │   └── DummyCommand.php
│   │   └── Kernel.php
│   ├── Events
│   │   └── DummyEvent.php
│   ├── Exceptions
│   │   └── DummyException.php
│   ├── Http
│   │   ├── Controllers
│   │   │   └── DummyController.php
│   │   ├── Middleware
│   │   │   └── DummyMiddleware.php
│   │   ├── Requests
│   │   │   └── DummyRequest.php
│   │   └── Resources
│   │       └── DummyResource.php
│   ├── Jobs
│   │   └── DummyJob.php
│   ├── Listener
│   │   └── DummyListener.php
│   ├── Mail
│   │   └── DummyMail.php
│   ├── Models
│   │   └── DummyModel.php
│   ├── Notification
│   │   └── DummyNotification.php
│   ├── Observers
│   │   └── DummyObserver.php
│   ├── Policies
│   │   └── DummyPolicy.php
│   ├── Providers
│   │   └── DummyProvider.php
│   ├── Rules
│   │   └── DummyRule.php
│   └── Services
│       └── DummyService.php
├── composer.json
├── database
│   ├── factories
│   │   └── DummyFactory.php
│   ├── migrations
│   │   └── 2019_07_04_105604_dummy_migration.php
│   └── seeds
│       ├── DatabaseSeeder.php
│       └── DummySeeder.php
├── resources
│   └── lang
│       ├── en
│       │   └── exceptions.php
│       └── fr
│           └── exceptions.php
├── routes
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   └── web.php
└── tests
    ├── Feature
    │   └── DummyTest.php
    └── Unit
        └── DummyTest.php
```

### Composer

At the root of your module you have a composer.json, same as the one you know with just a bit extra, here an example :

```
{
  "name": "Core",
  "description": "A perfect description for a brand new module !",
  "keywords": [
    "module",
    "laravel"
  ],
  "license": "MIT",
  "require": {
    "php": "^7.1"
  },
  "extra": {
    "laravel-modules": {
      "name": "Core",
      "alias": "core",
      "description": "The core module, for module management.",
      "install": {
        "migrate": true,
        "createDir": "modules"
      },
      "loadParameters": {
        "compartmentalize": {
          "migrations": true
        }
      },
      "providers": [
        "SystemModules\\Core\\App\\Providers\\EventServiceProvider",
        "SystemModules\\Core\\App\\Providers\\CommandExtendProvider"
      ],
      "aliases": {
        "Test": "Illuminate\\Support\\Facades\\App",
        "ModulesManager": "SystemModules\\Core\\Services\\ModulesManager"
      }
    }
  }
}
```

Here what that mean :
- **name** : literally whatever you want, simply a cosmetic value in database.
- **alias** : this is what you will be using in every command.
- **description** : a description, duh.
- **install.migrate** : migrate the migrations on module installation.
- **install.createDir** : create a dir of you liking on installation.
- **loadParameters.compartmentalize.migrations** : isolate the migration from the rest of your app, in this example the migration for the modules table will not be called on any of the ```migrate``` commands except for the ```migrate:fresh``` because it drop all tables regardless of migrations, you'll have to install all your modules again after this one.
- **providers** : list all the providers you want to load in your module.
- **aliases** : list all the aliases you want to have in your module.

Remember that you will have to call ```php artisan module:reinstall YOUR_MODULE_ALIAS``` for updating the settings above.

### .env

Here are some settings added that may be used in your .env :

```
API_PREFIX='web' // a prefix for all the web routes, '' by default
WEB_PREFIX='api' // a prefix for all the api routes, 'api' by default
```

That's pretty much it, there probably plenty of room for improvement so I'm waiting your comments on this !