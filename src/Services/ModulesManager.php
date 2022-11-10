<?php

namespace Bchalier\LaravelModules\App\Services;

use Ergebnis\Json\Printer\Printer;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Bchalier\LaravelModules\App\Models\Module;

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
     * @return Collection
     */
    public function getActiveModules($andSystem = false): Collection
    {
        return Module::where('active', true);
    }

    public function getSystemModulesConfig(): array
    {
        return json_decode($this->files->get(__DIR__ . '/../../../../../modules.json'), true);
    }

    public function getModulesConfig(): array
    {
        $path = config('modules.config.path');

        return $this->files->exists($path) ? json_decode($this->files->get($path), true) : [];
    }

    /**
     * @param $path
     * @param bool $disabled
     * @return bool
     * @throws FileNotFoundException
     */
    public function install($path, $disabled = false): bool
    {
        $configFile = base_path($path . 'composer.json');

        if (!file_exists($configFile)) {
            throw new FileNotFoundException($configFile);
        }

        $configFile = json_decode($this->files->get($configFile), true);
        $config = $configFile['extra']['laravel-modules'];

        // migrate migrations
        if ($config['install']['migrate']) {
            Artisan::call('migrate', [
                '--path' => $path . 'database/migrations'
            ]);
        }

        // createDir directive
        if (isset($config['install']['createDir'])) {
            if (!$this->files->isDirectory($config['install']['createDir'])) {
                $this->files->makeDirectory($config['install']['createDir'], 0755, true, true);
            }
        }

        // registering module
        $module = new Module();
        $module->alias = $config['alias'];
        $module->setPath($path);

        return $disabled ? $module->enable() : $module->disable();
    }

    /**
     * Update the module config in composer.json under extra.laravel-modules.
     *
     * @param Module $module
     * @param        $key
     * @param        $value
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function setConfig(Module $module, $key, $value): bool
    {
        return set_json_value($module->path('composer.json'), 'extra.laravel-modules.' . $key, $value);
    }

    public function setGlobalConfig($module, $key, $value): bool
    {
        return set_json_value(config('modules.config.path'), $module . '.' . $key, $value);
    }

    public function dropGlobalConfig($module): bool
    {
        $printer = new Printer();
        $config = $this->getModulesConfig();

        unset($config[$module]);

        return $this->files->put(
            config('modules.config.path'),
            $printer->print(json_encode($config, JSON_UNESCAPED_SLASHES), '    ')
        );
    }

    /**
     * Delete the specified module dir
     *
     * @param Module $module
     * @return bool
     */
    public function delete(Module $module): bool
    {
        return $this->files->deleteDirectory($module->path());
    }
}
