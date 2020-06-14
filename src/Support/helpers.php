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
        return count(explode("\n", explode('x-style', File::get($path))[0]));
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
        $path = storage_path("framework/views/{$name}.php");

        if (!File::exists($path)) {
            return;
        }

        return trim(Str::between(File::get($path), 'PATH', 'ENDPATH'));
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

if (!function_exists('get_view_name_from_path')) {
    /**
     * Get view name form path.
     * 
     * @param string $path
     * @return string
     */
    function get_view_name_from_path(string $path)
    {
        $finder = app('view.finder');

        foreach ($finder->getPaths() as $directory) {
            if (Str::startsWith($path, $directory)) {
                return path_to_view_name($directory, $path);
            }
        }
    }
}

if (!function_exists('path_to_view_name')) {
    /**
     * Path to view name.
     *
     * @param string $directory 
     * @param string $path
     * @return string
     */
    function path_to_view_name(string $directory, string $path)
    {
        return Str::replaceFirst('.', '', str_replace(['/', '\\'], '.', str_replace('.blade.php', '', str_replace($directory, '', $path))));
    }
}
