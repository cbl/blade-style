<?php

namespace Tests;

use Illuminate\Support\Facades\File;

class BladeStyleTest extends TestCase
{
    /** @test */
    public function it_doesnt_render_style()
    {
        $view = $this->getView('foo', '<x-style>body{background:red;}</x-style>');
        $this->assertEquals('', $view->render());
    }

    /** @test */
    public function test_styles_renders_style_tag()
    {
        $view = $this->getView('foo', '<x-styles/>');
        $this->assertStringContainsString('<style></style>', $view->render());
    }

    /** @test */
    public function it_puts_styles_to_styles_tag()
    {
        $this->getView('foo', '<x-style>body{background:red;}</x-style>');
        $this->getView('bar', '<x-style>a{color:green;}</x-style>');
        $view = $this->getView('baz', '<x-styles/>@include(\'foo\')@include(\'bar\')');

        $this->assertStringContainsString(
            '<style>body{background:red}a{color:green}</style>', $view->render()
        );
    }

    /** @test */
    public function it_stores_styles_to_storage()
    {
        $view = $this->getView('foo', '<x-styles/><x-style>body{background:red}</x-style>');
        $view->render();
        $path = storage_path('framework/styles/'.sha1($view->getPath()).'.css');
        $this->assertTrue(File::exists($path));
        $this->assertSame('body{background:red}', File::get($path));
    }

    /** @test */
    public function test_style_clear_command()
    {
        $view = $this->getView('foo', '<x-styles/><x-style>body{background:red}</x-style>');
        $view->render();
        $path = storage_path('framework/styles/'.sha1($view->getPath()).'.css');
        $this->assertTrue(File::exists($path));
        $this->artisan('style:clear');
        $this->assertFalse(File::exists($path));
    }

    /** @test */
    public function test_style_cache_command()
    {
        $view = $this->getView('foo', '<x-style>body{background:red}</x-style>');
        $path = storage_path('framework/styles/'.sha1($view->getPath()).'.css');
        $this->assertFalse(File::exists($path));
        $this->artisan('style:cache');
        $this->assertTrue(File::exists($path));
    }

    /** @test */
    public function it_compiles_sass()
    {
        $view = $this->getView('foo', '<x-style lang="sass">.a{.b{color:red;}}</x-style>');
        $path = storage_path('framework/styles/'.sha1($view->getPath()).'.css');
        $this->artisan('style:cache');
        $this->assertSame('.a .b{color:red}', File::get($path));
    }
}
