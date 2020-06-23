# Blade Style

A package to easily add styles to your blade views.

```php
<button class="btn">{{ $slot }}</button>

<x-style lang="css">
.btn{
    height: 2rem;
    line-height: 2rem;
    border-radius:3px;
}
</x-style>
```

## Introduction

Already some javascript frameworks (e.g. [Vue](https://vuejs.org/)) brought an
architecture where styles and html markup could be written in the same file.
This design pattern is a considerable simplification of the workflow in frontend
development.

With blade styles there is no need to run a compiler when working on your
styles. Also, only the styles of required blade components are loaded. This
saves you from loading large css files and the size can be reduced to a minimum.

## Compiler

Currently there is a `Sass` compiler for blade styles. If you want to build a
compiler for `Less` or `Stylus`, you can do so using the `Sass` example.

-   [Blade Style Sass](https://github.com/cbl/blade-style-sass)

## Installation

The package can be easily installed via composer.

```shell
composer requrie cbl/blade-style
```

Now you only have to save the storage where the styles are compiled and publish
the config.

```shell
php artisan vendor:publish --provider=BladeStyle\ServiceProvider
```

## Loading Styles

The blade component `x-styles` is replaced with the required styles, so it may
be placed in the head.

```php
<head>
    ...

    <x-styles />
</head>
```

## Basics

Each blade view can contain exactly one `x-style` component. Your styles can
then be written inside the wrapper like so.

```php
<img src="http://lorempixel.com/400/200/" class="my-image"/>

<x-style lang="css">
.my-image{
    border: 1px solid #ccc;
    border-radius: 3px;
}
</x-style>
```

You can build reusable blade components so easily:

```php
<button class="btn">{{ $slot }}</button>

<x-style lang="css">
.btn{
    height: 2rem;
    line-height: 2rem;
    border-radius:3px;
}
</x-style>
```

## Sass

To use sass in `x-style` components you must first install the compiler using
composer.

```shell
composer require cbl/blade-style-sass
```

The sass compiler uses [scssphp](https://github.com/scssphp/scssphp) that means
node is not needed.

Now you can use sass by specifying `sass` or `scss` as `lang` parameter like so.

```php
<button class="btn">{{ $slot }}</button>

<x-style lang="sass">
$height: 2rem;
.btn{
    height: $height;
}
</x-style>
```
