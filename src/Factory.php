<?php

namespace BladeStyle;

use BladeStyle\Components\StylesComponent;
use BladeStyle\Engines\EngineResolver;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Factory
{
    /**
     * Style stack.
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
     * @param  string $path
     * @return Style
     */
    public function make(string $path, $lang = null)
    {
        if ($this->inStack($path)) {
            return $this->stack[$path];
        }

        if (! $lang) {
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
     * @param  string $path
     * @return bool
     */
    public function inStack(string $path)
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
            $styles .= $style->render();
        }

        return "\n<style>{$styles}</style>\n";
    }

    /**
     * Determine if path has been rendered.
     *
     * @param  string $path
     * @return bool
     */
    public function isRendered($path)
    {
        return array_key_exists($path, $this->rendered);
    }

    /**
     * Determine wether new styles are discovered that can be included.
     *
     * @return bool
     */
    public function hasNew()
    {
        foreach ($this->stack as $path => $style) {
            if (! $this->isRendered($path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine wether string includes styles.
     *
     * @param  string $result
     * @return void
     */
    public function includesStyles(string $result)
    {
        return Str::contains($result, [
            StylesComponent::PLACEHOLDER_OPEN,
            StylesComponent::PLACEHOLDER_CLOSE,
        ]);
    }

    /**
     * Include styles to x-styles component.
     *
     * @param  string $result
     * @return string
     */
    public function include(string $result)
    {
        if (! $this->includesStyles($result)) {
            return $result;
        }

        $current = Str::between($result, StylesComponent::PLACEHOLDER_OPEN, StylesComponent::PLACEHOLDER_CLOSE);

        $search = StylesComponent::PLACEHOLDER_OPEN.$current.StylesComponent::PLACEHOLDER_CLOSE;
        $replace = StylesComponent::PLACEHOLDER_OPEN.$this->render().StylesComponent::PLACEHOLDER_CLOSE;

        return Str::replaceFirst($search, $replace, $result);
    }

    /**
     * Extract lang from string.
     *
     * @param  string $string
     * @return string
     */
    protected function extractLang(string $string)
    {
        preg_match('/<x-style(?:\s+(?:lang=["\'](?P<lang>[^"\'<>]+)["\']|\w+=["\'][^"\'<>]+["\']))+/i', $string, $matches);

        return $matches[1] ?? config('style.default_lang');
    }
}
