<?php

namespace TomatoPHP\FilamentArtisan;

use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentArtisan\Pages\Artisan;


class FilamentArtisanPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-artisan';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                Artisan::class,
            ]);

    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }
}
