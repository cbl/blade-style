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
    /**
     * Style comipler.
     *
     * @var string
     */
    protected $from;

    /**
     * Compile.
     *
     * @param Request $request
     * @param Int $from
     * @return void
     */
    public function __invoke(Request $request, $from)
    {
        $this->from = Carbon::createFromTimestamp($from / 1000);

        $this->compileChangedStyles();

        $updated = [
            'changed' => $this->compiler->getChanged(),
            'removed' => $this->compiler->removed(),
        ];

        return response()->json(['updated' => $updated], 200);
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

            preg_match('/(?<=\blang=")[^"]*/', File::get($file), $matches);
            $lang = $matches[0] ?? 'css';

            $this->compiler->compile($style, $styleId, $lang);
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
