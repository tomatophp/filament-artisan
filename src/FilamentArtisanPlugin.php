<?php

namespace TomatoPHP\FilamentArtisan;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Nwidart\Modules\Module;
use TomatoPHP\FilamentArtisan\Pages\Artisan;
use TomatoPHP\FilamentDeveloperGate\FilamentDeveloperGatePlugin;


class FilamentArtisanPlugin implements Plugin
{
    private bool $isActive = false;

    public function getId(): string
    {
        return 'filament-artisan';
    }

    public function register(Panel $panel): void
    {
        if(class_exists(Module::class) && \Nwidart\Modules\Facades\Module::find('FilamentArtisan')?->isEnabled()){
            $this->isActive = true;
        }
        else {
            $this->isActive = true;
        }

        if($this->isActive) {
            $panel
                ->plugin(FilamentDeveloperGatePlugin::make())
                ->pages([
                    Artisan::class,
                ]);
        }

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
