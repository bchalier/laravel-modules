<?php

namespace SystemModules\Core\App\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use League\Flysystem\FileNotFoundException;
use SystemModules\Core\App\Models\Module;

class ModulesManager
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * @param bool $andSystem
     * @return Module[]
     * @throws FileNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getActiveModules($andSystem = false)
    {
        if (!Schema::hasTable('modules')) {
            $this->install('vendor/bchalier/laravel-modules/src/SystemModules/Core/');
        }

        $query = Module::where('active', true);

        if (!$andSystem) {
            $query->whereNotIn('alias', Module::SYS_MODULES);
        }

        return $query->get();
    }

    /**
     * @param      $path
     * @param bool $disabled
     * @return bool
     * @throws FileNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function install($path, $disabled = false)
    {
        $configFile = base_path($path . 'composer.json');

        if (!file_exists($configFile))
            throw new FileNotFoundException($configFile);

        $configFile = json_decode($this->files->get($configFile), true);
        $config = $configFile['extra']['laravel-modules'];

        // migrate migrations
        if ($config['install']['migrate'])
            Artisan::call('migrate', [
                '--path' => $path . 'database/migrations'
            ]);

        // createDir directive
        if (isset($config['install']['createDir']))
            if (!$this->files->isDirectory($config['install']['createDir'])) {
                $this->files->makeDirectory($config['install']['createDir'], 0755, true, true);
//                $this->files->put($config['install']['createDir'] . '/.gitkeep', ''); not sure about that
            }

        // adding composer module repository
        exec('composer config extra.merge-plugin.include \'modules/*/composer.json\'');

        // registering module
        $module = new Module;

        $module->name = $configFile['name'];
        $module->alias = $config['alias'];
        $module->path = $path;

        if ($disabled)
            $module->active = false;

        foreach (['loadParameters', 'providers', 'aliases'] as $item)
            if (isset($config[$item]))
                $module->$item = $config[$item];
            else
                $module->$item = [];

        return $module->save();
    }

    /**
     * Delete the specified module dir
     *
     * @param Module $module
     * @return bool
     */
    public function delete(Module $module)
    {
        return $this->files->deleteDirectory(base_path($module->path));
    }
}
