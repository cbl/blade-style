# Blade Style

<p align="center">
	<a href="https://github.com/cbl/blade-style/actions"><img src="https://github.com/cbl/blade-style/workflows/tests/badge.svg" alt="Build Status"></a>
	<a href="https://packagist.org/packages/cbl/blade-style"><img src="https://img.shields.io/github/license/cbl/blade-style?color=%2331c653" alt="License"></a>
</p>

A package to easily minify styles and make use of sass, less, etc in your blade
components.

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
styles. Also, only the styles of required blade components are included. This
saves you from loading large css files and the size can be reduced to a minimum.

## Compiler

Currently there is a `Sass` compiler for blade styles. If you want to build a
compiler for `Less` or `Stylus`, you can do so using the `Sass` package as an
example.

-   [Blade Style Sass](https://github.com/cbl/blade-style-sass)

## Installation

The package can be easily installed via composer.

```shell
composer require cbl/blade-style
```

now the necessary assets must be published. This includes the style.php config
and the storage folder where the compiled styles are stored.

```shell
php artisan vendor:publish --provider="BladeStyle\ServiceProvider"
```

## Include Styles

The blade component `x-styles` includes all required styles, so it may be placed
in the head.

```php
<head>
    ...

    <x-styles />
</head>
```

## Usage

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

You can build reusable blade components:

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

### Sass

You may set a CSS extension langauge in the `lang` attribute, like so:

```php
<button class="btn">My Button</button>

<x-style lang="scss">
    $color: purple;

    .btn{
        background: $color;
    }
</x-style>
```

## Optimizing Styles

Blade styles share the same behavior as Views. As suggested in the
[View documentation](https://laravel.com/docs/7.x/views#optimizing-views), the
`style:cache` command can be added to your deployment workflow to ensure that
all styles are compiled and thus improve performance.

```shell
php artisan style:cache
```

You may use the `style:clear` command to clear the style cache:

```shell
php artisan style:clear
```
