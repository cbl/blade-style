<?php

namespace BladeStyle;

use BladeStyle\Exceptions\StyleException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use BladeStyle\StyleCompiler;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class StyleController
{
    protected $handler;

    protected $from;

    public function __construct()
    {
        $this->handler = app(StyleHandler::class);
        $this->compiler = app(StyleCompiler::class);
    }

    public function changed(Request $request, $from)
    {
        $this->from = Carbon::createFromTimestamp($from / 1000);

        $this->compileChangedStyles();

        return response()->json(['updated' => $this->findChangedStyles()], 200);
    }

    protected function findChangedStyles()
    {
        $changed = [];

        foreach (glob(storage_path('framework/styles/*.css')) as $path) {
            $lastModified = Carbon::parse(filemtime($path));

            if ($lastModified < $this->from) {
                continue;
            }

            $changed[$this->handler->getIdFromPath($path)] = File::get($path);
        }

        return $changed;
    }

    protected function compileChangedStyles()
    {
        $finder = app('view')->getFinder();

        foreach ($finder->getPaths() as $path) {
            $this->compileStylesInPath($path);
        }

        foreach ($finder->getHints() as $namespace => $paths) {
            foreach ($paths as $path) {
                $this->compileStylesInPath($path, $namespace);
            }
        }
    }

    public function compileStylesInPath(string $path, $namespace = false)
    {
        //dd(time() * 1000);
        $files = File::allFiles($path);

        foreach ($files as $file) {
            if (!Str::endsWith($file, 'blade.php')) {
                continue;
            }

            if (!app('blade.compiler')->isExpired($file)) {
                continue;
            }

            if (!Str::contains(File::get($file), '<x-style')) {
                continue;
            }

            $style = $this->getStyle($file);
            $styleId = $this->handler->getIdFromPath(app('blade.compiler')->getCompiledPath($file));

            if ($style == $this->handler->get($styleId)) {
                continue;
            }

            $this->compiler->compile($style, $styleId, 'scss');
        }
    }

    protected function getStyle($file)
    {
        preg_match('/<x-style[^>]*>(.|\n)*?<\/x-style>/', File::get($file), $matches);

        if (empty($matches)) {
            return;
        }

        return preg_replace('/<[^>]*>/', '', $matches[0]);
    }
}
