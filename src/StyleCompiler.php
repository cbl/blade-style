<?php

namespace BladeStyle;

use Illuminate\Support\Str;
use BladeStyle\StyleHandler;
use InvalidArgumentException;
use BladeStyle\Compiler\Compiler;
use BladeStyle\Compiler\CssCompiler;
use Illuminate\Support\Facades\File;
use BladeStyle\Compiler\SassCompiler;

class StyleCompiler
{
    /**
     * Compiled paths.
     *
     * @var array
     */
    protected $compiled = [];

    /**
     * Style compiler.
     *
     * @var array
     */
    protected $compiler = [
        'css' => CssCompiler::class,
        'scss' => SassCompiler::class,
        'sass' => SassCompiler::class,
    ];

    /**
     * Compile style.
     *
     * @param string $style
     * @param string $name
     * @param string $lang
     * @return boolean|null
     */
    public function compile(string $style, $styleId, $lang = 'css')
    {
        $path = storage_path("framework/styles/{$styleId}.css");

        if (in_array($path, $this->compiled)) {
            return false;
        }

        if (File::exists($path) && !$this->hasChanged($path, $style)) {
            return false;
        }

        if (!array_key_exists($lang, $this->compiler)) {
            throw new InvalidArgumentException("No css compiler for language \"{$lang}\" found.");
        }

        $compiler = new $this->compiler[$lang](
            $style,
            $styleId,
            $path,
            $this->getBladePathFromStyleId($styleId)
        );

        return $compiler->compile();
    }

    public function getBladePathFromStyleId($id)
    {
        return trim(Str::between(File::get(storage_path("framework/views/{$id}.php")), 'PATH', 'ENDPATH'));
    }

    /**
     * Compile sass.
     *
     * @param string $style
     * @return string
     */
    public function compileSass($style, $styleId, $path)
    {
    }

    /**
     * Get compiled paths.
     *
     * @return array
     */
    public function getCompiled()
    {
        return $this->compiled;
    }

    /**
     * Get compiled path.
     *
     * @param string $style
     * @param string $name
     * @return string
     */
    public function getCompiledPath(string $style, string $name)
    {
        return storage_path('framework/styles/' . sha1($name) . '.php');
    }

    /**
     * Determine if the style at the given path has changed.
     *
     * @param  string  $path
     * @return bool
     */
    public function hasChanged($path, $style)
    {
        $oldStyle = app(StyleHandler::class)->getStyle($path);

        return $oldStyle != $style;
    }
}
