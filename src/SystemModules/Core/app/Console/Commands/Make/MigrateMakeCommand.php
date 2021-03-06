<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Make;

use Bchalier\SystemModules\Core\App\Console\Commands\Concerns\ExtendMakeCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand as BaseMigrateMakeCommand;
use Illuminate\Support\Composer;
use Illuminate\Database\Migrations\MigrationCreator;

class MigrateMakeCommand extends BaseMigrateMakeCommand
{
    use ExtendMakeCommand;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Database\Migrations\MigrationCreator  $creator
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        $this->signature .= "
                {--M|module= : The module where the command should be created.}
        ";

        parent::__construct($creator, $composer);
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        if (! $this->module) {
            return parent::getMigrationPath();
        }

        if (! is_null($targetPath = $this->input->getOption('path'))) {
            return ! $this->usingRealPath()
                ? $this->laravel->basePath() . DIRECTORY_SEPARATOR . $targetPath
                : $targetPath;
        }

        return $this->module->path('database/migrations');
    }
}
