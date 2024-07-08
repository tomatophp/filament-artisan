<?php

namespace TomatoPHP\FilamentArtisan;

use Illuminate\Support\ServiceProvider;


class FilamentArtisanServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-artisan.php', 'filament-artisan');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-artisan.php' => config_path('filament-artisan.php'),
        ], 'filament-artisan-config');

        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-artisan');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-artisan'),
        ], 'filament-artisan-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-artisan');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-artisan'),
        ], 'filament-artisan-lang');
    }

    public function boot(): void
    {
        //you boot methods here
    }
}
