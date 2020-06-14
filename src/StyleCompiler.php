<?php

namespace BladeStyle;

use BladeStyle\Support\Style;
use InvalidArgumentException;
use Illuminate\Support\Facades\File;
use BladeStyle\Compiler\CompilerInterface;

class StyleCompiler
{
    /**
     * Style compiler.
     *
     * @var array
     */
    protected $compiler = [];

    /**
     * List of compiled ids.
     *
     * @var array
     */
    protected $compiled = [];

    /**
     * List of changed ids.
     *
     * @var array
     */
    protected $changed = [];

    /**
     * List of removed ids.
     *
     * @var array
     */
    protected $removed = [];

    /**
     * Create new StyleCompiler instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->compiled = $this->getCompiledStyleIds();
    }

    /**
     * Get compiled style ids.
     *
     * @return array
     */
    public function getCompiledStyleIds()
    {
        return collect(glob(style_storage_path() . '/*.css'))
            ->map(function ($path) {
                return style_id_from_path($path);
            })
            ->toArray();
    }

    /**
     * Register style compiler.
     *
     * @param string $lang
     * @param \BladeStyle\Compiler\CompilerInterface $compiler
     * @return void
     */
    public function registerCompiler(string $lang, CompilerInterface $compiler)
    {
        $this->compiler[$lang] = $compiler;
    }

    /**
     * Compile style.
     *
     * @param string $style
     * @param string $styleId
     * @param string $lang
     * @return void
     */
    public function compile(string $style, string $styleId, $lang = 'css')
    {
        $path = $this->getCompiledPath($styleId);

        if (!array_key_exists($lang, $this->compiler)) {
            throw new InvalidArgumentException("No css compiler for language \"{$lang}\" found.");
        }

        $compiler = $this->compiler[$lang]->make(
            blade_path_from_compiled_name($styleId)
        );

        $compiler->compile($style, $path);

        $this->setChanged($styleId);
    }

    /**
     * Set changed.
     *
     * @param string $styleId
     * @return void
     */
    protected function setChanged(string $styleId)
    {
        // Add to compiled.
        if (!in_array($styleId, $this->compiled)) {
            $this->compiled[] = $styleId;
        }

        // Add to changed.
        if (!in_array($styleId, $this->changed)) {
            $this->changed[] = $styleId;
        }

        // Remove from removed.
        if (($key = array_search($styleId, $this->removed)) !== false) {
            unset($this->removed[$key]);
        }
    }

    /**
     * Does style need to be recompiled.
     *
     * @param string $style
     * @param string $path
     * @return boolean
     */
    protected function needsToBeCompiled($style, $path)
    {
        if (!File::exists($path)) {
            return true;
        }

        return $this->hasChanged($path, $style);
    }

    /**
     * Get compiled path.
     *
     * @param string $style
     * @param string $name
     * @return string
     */
    public function getCompiledPath(string $styleId)
    {
        return storage_path("framework/styles/{$styleId}.css");
    }

    /**
     * Determine if the style at the given path has changed.
     *
     * @param  string  $path
     * @return bool
     */
    public function hasChanged($path, $style)
    {
        $oldStyle = Style::get($path);

        return $oldStyle != $style;
    }
}
