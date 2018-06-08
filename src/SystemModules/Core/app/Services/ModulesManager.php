<?php

namespace SystemModules\Core\App\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
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

    public function getActiveModules()
    {
        if (!Schema::hasTable('modules'))
            self::install('vendor/bchalier/laravel-modules/src/SystemModules/Core/');

        return Module::where('active', true)->get();
    }

    public function install($path, $disabled = false)
    {
        $configFile = base_path($path . 'module.json');

        if (!file_exists($configFile))
            abort(400);

        $config = json_decode($this->files->get($configFile), true);

        // migrate migrations
        if ($config['install']['migrate'])
            Artisan::call('migrate', [
                '--path' => $path . 'Database/migrations'
            ]);

        // createDir directive
        if (isset($config['install']['createDir']))
            if (!$this->files->isDirectory($config['install']['createDir'])) {
                $this->files->makeDirectory($config['install']['createDir'], 0755, true, true);
//                $this->files->put($config['install']['createDir'] . '/.gitkeep', ''); not sure about that
            }

        // registering module
        $module = new Module;

        $module->name = $config['name'];
        $module->alias = $config['alias'];
        $module->keywords = $config['keywords'];
        $module->path = $path;

        if ($disabled)
            $module->active = false;

        foreach (['loadParameters', 'providers', 'aliases'] as $item)
            if (isset($config[$item]))
                $module->$item = $config[$item];
            else
                $module->$item = [];

        $module->save();
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
