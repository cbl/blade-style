<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Minify Styles
    |--------------------------------------------------------------------------
    |
    | This option determines wetther the compiled css string should be stored 
    | minified. It is highly recommended to do so. However you are free to 
    | disable minifying your styles. 
    |
    | The php minifier from Matthias Mullie is used by default.
    | https://github.com/matthiasmullie/minify
    |
    */

    'minify' => true,

    /*
    |--------------------------------------------------------------------------
    | Compiled Style Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled styles  will be stored for 
    | your application just like for your views.
    |
    */

    'compiled' => env(
        'STYLE_COMPILED_PATH',
        realpath(storage_path('framework/styles'))
    ),

];
