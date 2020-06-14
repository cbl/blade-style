<?php

namespace BladeStyle;

use Illuminate\Support\Facades\File;

class StyleHandler
{
    /**
     * Styles.
     *
     * @var array
     */
    protected $styles = [];

    /**
     * View paths that are loading styles.
     *
     * @var array
     */
    protected $views = [];

    /**
     * Styles that have changed.
     *
     * @var array
     */
    protected $changed = [];

    /**
     * Create new StyleHandler instance.
     */
    public function __construct()
    {
        $this->loadStyles();
    }

    /**
     * Load styles from storage.
     *
     * @return string
     */
    public function loadStyles()
    {
        foreach (glob(storage_path('framework/styles/*.css')) as $path) {
            $this->styles[$path] = File::get($path);
        }
    }

    /**
     * Reload changed style from storage.
     *
     * @param string $path
     * @param string $style
     * @return string
     */
    public function changed(string $path, string $style)
    {
        $this->styles[$path] = $style;
        $this->changed[] = $path;
    }

    /**
     * Get style.
     *
     * @param string $id
     * @return string
     */
    public function get(string $id)
    {
        return $this->styles[storage_path("framework/styles/{$id}.php")] ?? null;
    }

    /**
     * Check if styles have changed.
     *
     * @return boolean
     */
    public function hasChanges()
    {
        return !empty($this->changed);
    }

    /**
     * Get style tags.
     *
     * @param array $vars
     * @return array
     */
    public function getStyleTags($vars)
    {
        $this->views[] = $vars['__path'];
        $tags = [];
        foreach ($this->styles as $path => $style) {
            $id = $this->getIdFromPath($path);
            $tags[] = "<style style:id=\"{$id}\">\n{$style}\n</style>";
        }

        return implode("\n", $tags);
    }

    /**
     * Get id from path.
     *
     * @param string $path
     * @return string
     */
    public function getIdFromPath($path)
    {
        return last(explode('.', str_replace(['/', '\\'], '.', str_replace(['.php', '.css'], '', $path))));
    }

    /**
     * Get style from path.
     *
     * @param string $path
     * @return string|null
     */
    public function getStyle(string $path)
    {
        if (array_key_exists($path, $this->styles)) {
            return $this->styles[$path];
        }

        if (!File::exists($path)) {
            return;
        }

        return $this->styles[$path] = File::get($path);
    }
}
