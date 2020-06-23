<?php

namespace BladeStyle;

use Illuminate\Support\Facades\File;
use BladeStyle\Engines\EngineResolver;

class Factory
{
    /**
     * Style stack
     *
     * @var string
     */
    protected $stack = [];

    /**
     * Rendered styles.
     *
     * @var array
     */
    protected $rendered = [];

    /**
     * Engine resolver.
     *
     * @var \BladeStyle\Engine\EngineResolver
     */
    protected $resolver;

    /**
     * Create new Factory instance.
     *
     * @param EngineResolver $resolver
     */
    public function __construct(EngineResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Create style instance from view.
     *
     * @param string $path
     * @return Style
     */
    public function make($path, $lang = null)
    {
        if ($this->inStack($path)) {
            return $this->stack[$path];
        }

        if (!$lang) {
            $lang = $this->extractLang(File::get($path));
        }

        $engine = $this->resolver->resolve($lang);

        $style = new Style($path, $engine);

        // Adding style to stack.
        $this->stack[$path] = $style;

        return $style;
    }

    /**
     * Determine if style has been created.
     *
     * @param string $path
     * @return boolean
     */
    public function inStack($path)
    {
        return array_key_exists($path, $this->stack);
    }

    /**
     * Render stack.
     *
     * @return string
     */
    public function render()
    {
        $styles = '';

        foreach ($this->stack as $path => $style) {
            if ($this->isRendered($path)) {
                continue;
            }

            $styles .= $this->wrap($style->render());

            $this->rendered[] = $path;
        }

        return $styles;
    }

    /**
     * Determine if path has been rendered.
     *
     * @param string $path
     * @return boolean
     */
    public function isRendered($path)
    {
        return array_key_exists($path, $this->rendered);
    }

    /**
     * Wrap style when not flat.
     *
     * @param string $style
     * @param boolean $flat
     * @return string
     */
    protected function wrap(string $style, $flat = false)
    {
        if (trim($style) == '') {
            return;
        }

        return "<style>{$style}</style>";
    }

    /**
     * Extract lang from string.
     *
     * @param string $string
     * @return string
     */
    protected function extractLang(string $string)
    {
        preg_match('/<x-style(?:\s+(?:lang=["\'](?P<lang>[^"\'<>]+)["\']|\w+=["\'][^"\'<>]+["\']))+/i', $string, $matches);

        return $matches[1] ?? 'css';
    }
}
