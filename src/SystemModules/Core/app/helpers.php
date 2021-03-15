<?php

use Ergebnis\Json\Printer\Printer;
use Illuminate\Support\Arr;

if (! function_exists('set_json_value')) {
    function set_json_value(string $jsonFilePath, string $key, $value)
    {
        $printer = app(Printer::class);
        $files = app('files');

        $configFile = $files->exists($jsonFilePath) ? $files->get($jsonFilePath) : '[]';
        $config = json_decode($configFile, true);

        Arr::set($config, $key, $value);

        return $files->put($jsonFilePath, $printer->print(json_encode($config, JSON_UNESCAPED_SLASHES), '    '));
    }
}
