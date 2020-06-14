<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

if (!function_exists('blade_style_starts_at')) {
    /**
     * Get line where <x-style> tag starts.
     *
     * @param string $path
     * @return int
     */
    function blade_style_starts_at(string $path)
    {
        return count(explode("\n", explode('x-style', File::get($path))[0])) - 1;
    }
}

if (!function_exists('blade_path_from_compiled_name')) {
    /**
     * Get blade path from compiled name.
     *
     * @param string $path
     * @return string
     */
    function blade_path_from_compiled_name(string $name)
    {
        return trim(Str::between(File::get(storage_path("framework/views/{$name}.php")), 'PATH', 'ENDPATH'));
    }
}

if (!function_exists('style_id_from_path')) {
    /**
     * Get style id from path.
     *
     * @param string $path
     * @return string
     */
    function style_id_from_path(string $path)
    {
        return last(explode('.', str_replace(['/', '\\'], '.', str_replace(['.php', '.css'], '', $path))));
    }
}

if (!function_exists('style_storage_path')) {
    /**
     * Get style storage path.

     * @return string
     */
    function style_storage_path()
    {
        return storage_path('framework/styles');
    }
}
