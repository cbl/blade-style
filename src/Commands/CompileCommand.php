<?php

namespace BladeStyle\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class CompileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'style:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile styles.';

    /**
     * Package base directiory.
     *
     * @var string
     */
    protected $base;

    /**
     * Style compiler
     *
     * @var \BladeStyle\StyleCompiler
     */
    protected $compiler;

    /**
     * Files.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * View finder.
     *
     * @var \Illuminate\View\FileViewFinder
     */
    protected $finder;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->base = __DIR__ . '/..';
        $this->compiler = app('blade.style.compiler');
        $this->files = app('files');
        $this->finder = app('view.finder');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $paths = $this->getPathsToCompile();

        foreach ($paths as $namespace => $namespacePaths) {
            foreach ($namespacePaths as $path) {
                $this->compileStylesInPath($path, $namespace);
            }
        }

        if (!$this->compiler->hasChanges()) {
            $this->line('No changes detected.');
        }
    }

    public function getPathsToCompile()
    {
        $paths = ['' => $this->finder->getPaths()];

        foreach ($this->finder->getHints() as $namespace => $namespacePaths) {
            $paths[$namespace] = $namespacePaths;
        }

        return $paths;
    }

    public function compileStylesInPath(string $path, string $namespace = '')
    {
        $files = $this->files->allFiles($path);

        foreach ($files as $file) {
            if (!Str::endsWith($file, 'blade.php')) {
                continue;
            }

            if (!$this->compiler->compile($file)) {
                continue;
            }

            $lang = $this->compiler->getLangFromString($this->files->get($file));
            $view = get_view_name_from_path($file);

            $this->line("Compiled <info>{$lang}</info> in view <info>{$view}</info>.");
        }
    }
}
