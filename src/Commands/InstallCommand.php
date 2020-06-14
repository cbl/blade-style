<?php

namespace BladeStyle\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'style:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install sass, less & stylus compiler.';

    /**
     * Package base directiory.
     *
     * @var string
     */
    protected $base;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->base = __DIR__ . '/..';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->installSass();
        $this->installLess();
        $this->installStylus();
    }

    /**
     * Install stylus.
     *
     * @return void
     */
    protected function installStylus()
    {
        $this->installNpmPackage('stylus');
    }

    /**
     * Install less.
     *
     * @return void
     */
    protected function installLess()
    {
        $this->installNpmPackage('less-node');
    }

    /**
     * Install sass.
     *
     * @return void
     */
    protected function installSass()
    {
        $this->installNpmPackage('node-sass');
    }

    /**
     * Install npm package.
     *
     * @param string $package
     * @return void
     */
    protected function installNpmPackage(string $package)
    {
        shell_exec("npm i {$package} --prefix {$this->base}");
    }
}
