<?php

namespace BladeStyle;

use Illuminate\Support\Facades\File;

class Style
{
    /**
     * Style name.
     *
     * @var string
     */
    protected $name;

    /**
     * Config.
     *
     * @var array
     */
    protected $config;

    /**
     * Compiled path.
     *
     * @var string
     */
    public $path;

    /**
     * Aliases.
     *
     * @var array
     */
    protected $alias = [];

    /**
     * Required files or paths.
     *
     * @var array
     */
    protected $requires = [];

    /**
     * Compiler.
     *
     * @var \BladeStyle\StyleCompiler
     */
    protected $compiler;

    /**
     * Compiled files.
     *
     * @var array
     */
    protected $compiled = [];

    /**
     * Create new style instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->config = config('styles.styles')[$name];

        $this->path = $this->config['path'];
        $this->alias = $this->config['alias'] ?? [];
        $this->requires = $this->config['requires'] ?? [];
        $this->compiler = app('blade.style.compiler');
    }

    /**
     * Compile style.
     *
     * @return void
     */
    public function compile()
    {
        $this->compiled = [];
        foreach ($this->getRequiredFiles() as $file) {
            if ($this->compileFile($file)) {
                $this->compiled[] = $file;
            }
        }

        if (empty($this->compiled)) {
            return;
        }

        $style = '';
        foreach ($this->getRequiredFiles() as $file) {
            if (!$this->compiler->canBeCompiled($file)) {
                continue;
            }


            $compiled = $this->compiler->getCompiledPath(
                $this->compiler->getStyleIdFromPath($file)
            );

            if (!File::exists($compiled)) {
                continue;
            }

            $style .= File::get($compiled);
        }


        if (!File::exists(dirname($this->path))) {
            File::makeDirectory(dirname($this->path), 0755, true);
        }

        File::put($this->path, $style);
    }

    /**
     * Get compiled files.
     *
     * @return void
     */
    public function getCompiled()
    {
        return $this->compiled;
    }

    /**
     * Get required files.
     *
     * @return void
     */
    protected function getRequiredFiles()
    {
        $files = [];
        foreach ($this->requires as $path) {
            if (!is_dir($path)) {
                $files[] = $path;
                continue;
            }

            $files = array_merge($files, File::allFiles($path));
        }
        return $files;
    }

    /**
     * Compile file.
     *
     * @param string $file
     * @return void
     */
    protected function compileFile($file)
    {
        if (!$this->compiler->canBeCompiled($file)) {
            return false;
        }

        return $this->compiler->compile($file);
    }

    /**
     * Convert style to string.
     *
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
