<?php

namespace TomatoPHP\FilamentArtisan;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use TomatoPHP\FilamentArtisan\Pages\Artisan;
use TomatoPHP\FilamentDeveloperGate\FilamentDeveloperGatePlugin;

class FilamentArtisanPlugin implements Plugin
{
    use EvaluatesClosures;

    protected bool|Closure $isAuthorized = true;

    protected ?bool $shouldUseDeveloperGate = null;

    protected ?bool $isOnlyLocal = null;

    public function getId(): string
    {
        return 'filament-artisan';
    }

    public function register(Panel $panel): void
    {
        if (! $panel->hasPlugin('filament-developer-gate')) {
            $panel->plugin(FilamentDeveloperGatePlugin::make());
        }

        $panel->pages([
            Artisan::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Restrict the Artisan page, e.g. `->authorize(fn (): bool => auth()->user()->isAdmin())`.
     * Pass `false` to disable the page entirely on this panel.
     */
    public function authorize(bool|Closure $condition = true): static
    {
        $this->isAuthorized = $condition;

        return $this;
    }

    public function isAuthorized(): bool
    {
        return (bool) $this->evaluate($this->isAuthorized);
    }

    /**
     * Override the `filament-artisan.developer_gate` config value for this panel.
     */
    public function developerGate(bool $condition = true): static
    {
        $this->shouldUseDeveloperGate = $condition;

        return $this;
    }

    public function shouldUseDeveloperGate(): bool
    {
        return $this->shouldUseDeveloperGate ?? (bool) config('filament-artisan.developer_gate', true);
    }

    /**
     * Override the `filament-artisan.local` config value for this panel.
     */
    public function onlyLocal(bool $condition = true): static
    {
        $this->isOnlyLocal = $condition;

        return $this;
    }

    public function isOnlyLocal(): bool
    {
        return $this->isOnlyLocal ?? (bool) config('filament-artisan.local', true);
    }
}
