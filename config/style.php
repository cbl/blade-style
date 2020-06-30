<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Minify Styles
    |--------------------------------------------------------------------------
    |
    | This option determines wether the compiled css string should be stored
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

    /*
    |--------------------------------------------------------------------------
    | Style Compiler
    |--------------------------------------------------------------------------
    |
    | Style compilers make it possible to use css extensions like Sass, Less or
    | Stylus in Blade. The registered compilers can be activated with the "lang"
    | attribute in your x-style tag, like so: '<x-style lang="scss">'
    |
    */

    'compiler' => [
        BladeStyle\Compiler\CssCompiler::class => [
            'css',
        ],
        BladeStyle\Sass\SassCompiler::class => [
            'sass',
            'scss',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Style Compiler
    |--------------------------------------------------------------------------
    |
    | The default lang specifies which css language compiler is used if no other
    | is specified in the "lang" attribute of your x-style tag.
    |
    | Note that it is advantageous for the readability of your code to specify
    | the compiler language in each x-style tag except when you are using plain
    | css.
    |
    */

    'default_lang' => 'css',

];
