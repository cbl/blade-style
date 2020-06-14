<?php

use Illuminate\Support\Facades\File;

function blade_style_starts_at(string $path)
{
    return count(explode("\n", explode('x-style', File::get($path))[0])) - 1;
}
