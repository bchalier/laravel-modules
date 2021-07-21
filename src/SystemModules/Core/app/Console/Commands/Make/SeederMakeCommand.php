<?php

namespace Bchalier\SystemModules\Core\App\Console\Commands\Make;

use Illuminate\Database\Console\Seeds\SeederMakeCommand as BaseSeederMakeCommand;
use Symfony\Component\Console\Input\InputOption;
use Bchalier\SystemModules\Core\App\Models\Module;

class SeederMakeCommand extends BaseSeederMakeCommand
{
    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        $options[] = ['module', 'M', InputOption::VALUE_OPTIONAL, 'The module where the command should be created.'];

        return $options;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        if (!empty($moduleAlias = $this->option('module'))) {
            $module = Module::findAlias($moduleAlias);

            if (!$module) {
                $this->error("$moduleAlias module don't exists!");
                return false;
            }

            return $module->path('database/seeders/' . $name . '.php');
        } else {
            return parent::getPath($name);
        }
    }
}
