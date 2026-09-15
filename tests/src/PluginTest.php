<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentArtisan\FilamentArtisanPlugin;
use TomatoPHP\FilamentArtisan\FilamentArtisanServiceProvider;
use TomatoPHP\FilamentArtisan\Pages\Artisan;
use TomatoPHP\FilamentDeveloperGate\Pages\DeveloperGate;

it('boots the service provider and merges the config', function () {
    expect(app()->getProviders(FilamentArtisanServiceProvider::class))->not->toBeEmpty()
        ->and(config('filament-artisan.commands'))->toBeArray()->not->toBeEmpty();
});

it('registers the plugin, the artisan page and the developer gate on the panel', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getPlugin('filament-artisan'))->toBeInstanceOf(FilamentArtisanPlugin::class)
        ->and($panel->hasPlugin('filament-developer-gate'))->toBeTrue()
        ->and($panel->getPages())->toContain(Artisan::class, DeveloperGate::class);
});

it('lets the plugin override the config switches', function () {
    config()->set('filament-artisan.developer_gate', true);
    config()->set('filament-artisan.local', true);

    $plugin = FilamentArtisanPlugin::make();

    expect($plugin->shouldUseDeveloperGate())->toBeTrue()
        ->and($plugin->isOnlyLocal())->toBeTrue()
        ->and($plugin->isAuthorized())->toBeTrue();

    $plugin->developerGate(false)->onlyLocal(false)->authorize(fn (): bool => false);

    expect($plugin->shouldUseDeveloperGate())->toBeFalse()
        ->and($plugin->isOnlyLocal())->toBeFalse()
        ->and($plugin->isAuthorized())->toBeFalse();
});
