<?php

namespace BladeStyle;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blade-style:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = __DIR__ . '/..';
        shell_exec("npm i node-sass --prefix {$path}");
        shell_exec("npm i stylus --prefix {$path}");
        shell_exec("npm i less-node --prefix {$path}");
    }
}
