<?php

namespace BladeStyle\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;

class StyleClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'style:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all compiled style files';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new config clear command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function handle()
    {
        $path = $this->laravel['config']['style.compiled'];

        if (! $path) {
            throw new RuntimeException('Style path not found.');
        }

        foreach ($this->files->glob("{$path}/*") as $script) {
            $this->files->delete($script);
        }

        $this->info('Compiled styles cleared!');
    }
}
