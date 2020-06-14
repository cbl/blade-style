<?php

namespace BladeStyle;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use BladeStyle\Support\Style;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

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
    public function __invoke(Request $request, string $name)
    {
        Artisan::call("style:compile {$name}");

        $updated = [
            'updated' => $this->getUpdated(),
            'removed' => [], //app('blade.style.compiler')->removed(),
        ];

        return response()->json($updated, 200);
    }

    /**
     * Get changed styles.
     *
     * @return array
     */
    protected function getUpdated()
    {
        $changed = [];
        foreach (app('blade.style.compiler')->getChanged() as $styleId) {
            $changed[$styleId] = Style::get($styleId);
        }
        return $changed;
    }
}
