<?php

namespace BladeStyle\Commands;

use BladeStyle\Factory;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Foundation\Console\ViewCacheCommand;

class StyleCacheCommand extends ViewCacheCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'style:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Compile all of the application's Blade styles";

    /**
     * Style factory.
     *
     * @var \BladeStyle\Factory
     */
    protected $style;

    /**
     * Create new StyleClearCommand instance.
     *
     * @param Factory $style
     */
    public function __construct(Factory $style)
    {
        parent::__construct();

        $this->style = $style;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('style:clear');

        $this->paths()->each(function ($path) {
            $this->compileStyles($this->bladeFilesIn([$path]));
        });

        $this->info('Blade styles cached successfully!');
    }

    /**
     * Compile the given view files.
     *
     * @param  \Illuminate\Support\Collection  $views
     * @return void
     */
    protected function compileStyles(Collection $views)
    {
        $views->map(function (SplFileInfo $file) {
            $this->style
                ->make($file)
                ->getCompiler()
                ->compile($file);
        });
    }
}
