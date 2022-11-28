<?php

namespace Bchalier\LaravelModules\App\Console\Commands\Database;

use Bchalier\LaravelModules\App\Repositories\ModulesRepository;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Console\Seeds\SeedCommand as BaseSeedCommand;

class SeedCommand extends BaseSeedCommand
{
    public function __construct(
        Resolver $resolver,
        protected ModulesRepository $modulesRepository,
    ) {
        parent::__construct($resolver);
    }

    public function handle()
    {
        parent::handle();

        if ($this->option('class') == DatabaseSeeder::class) {
            foreach ($this->modulesRepository->all() as $module) {
                $class = "{$module->namespace()}\Database\Seeders\DatabaseSeeder";

                $this->call('db:seed', [
                    '--class' => $class
                ]);
            }
        }
    }
}
