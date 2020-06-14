<?php

namespace BladeStyle;

use Illuminate\Support\Str;
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
     * @param string $path
     * @return void
     * 
     * @throws \InvalidArgumentException
     */
    public function compile(string $path)
    {
        if (Str::endsWith($path, 'blade.php')) {
            return $this->compileView($path);
        }

        throw new InvalidArgumentException("Could not compile file {$path}.");
        //$lang = $path
    }

    /**
     * Check if file can be compiled.
     *
     * @param string $path
     * @return boolean
     */
    public function canBeCompiled($path)
    {
        return Str::endsWith($path, [
            'blade.php'
        ]);
    }

    /**
     * Compile view.
     *
     * @param string $viewPath
     * @return void
     */
    protected function compileView($viewPath)
    {
        $styleId = sha1($viewPath);
        $compiledPath = $this->getCompiledPath($styleId);

        if (!$this->isExpired($compiledPath, $viewPath)) {
            return false;
        }

        $string = File::get($viewPath);
        $style = $this->getStyleFromString($string);
        $lang = $this->getLangFromString($string);

        if (!$style) {
            return false;
        }

        if (!array_key_exists($lang, $this->compiler)) {
            throw new InvalidArgumentException("No css compiler for language \"{$lang}\" found.");
        }

        $compiler = $this->compiler[$lang]->make($viewPath);

        $compiler->compile($style, $compiledPath);

        $this->setChanged($styleId);

        return true;
    }

    /**
     * Get style id from path.
     *
     * @param string $path
     * @return string
     */
    public function getStyleIdFromPath(string $path)
    {
        if (Str::endsWith($path, 'blade.php')) {
            return sha1($path);
        }
    }

    /**
     * Get lang from string.
     *
     * @param string $string
     * @return string
     */
    public function getLangFromString(string $string)
    {
        preg_match('/<x-style(?:\s+(?:lang=["\'](?P<lang>[^"\'<>]+)["\']|\w+=["\'][^"\'<>]+["\']))+/i', $string, $matches);

        return $matches[1] ?? 'css';
    }

    /**
     * Get style from string.
     *
     * @param string $string
     * @return string|null
     */
    public function getStyleFromString($string)
    {
        preg_match('/<x-style[^>]*>(.|\n)*?<\/x-style>/', $string, $matches);

        if (empty($matches)) {
            return;
        }

        return preg_replace('/<[^>]*>/', '', $matches[0]);
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
     * Get changes.
     *
     * @return array
     */
    public function hasChanges()
    {
        return !empty($this->changed);
    }

    /**
     * Get changed ids.
     *
     * @return void
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * Does style need to be recompiled.
     *
     * @param string $compiledPath
     * @param string $viewPath
     * @return boolean
     */
    protected function isExpired($compiledPath, $viewPath)
    {
        if (!File::exists($compiledPath)) {
            return true;
        }

        return File::lastModified($viewPath) >=
            File::lastModified($compiledPath);
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
}
