<?php

return [

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
