<?php

namespace BladeStyle\Commands;

use BladeStyle\Style;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class CompileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'style:compile {name}';

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
        $style = new Style($this->argument('name'));
        $style->compile();

        $compiledFiles = $style->getCompiled();
        foreach ($compiledFiles as $file) {
            $lang = $this->compiler->getLangFromString($this->files->get($file));
            $view = get_view_name_from_path($file);
            $this->line("Compiled <info>{$lang}</info> in view <info>{$view}</info>.");
        }

        if (!$compiledFiles) {
            $this->line('No changes detected.');
        } else {
            $this->info("Created css file in {$style->path}");
        }
    }
}
