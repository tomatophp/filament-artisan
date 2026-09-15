<?php

namespace TomatoPHP\FilamentArtisan\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use TomatoPHP\FilamentArtisan\Pages\Artisan;
use TomatoPHP\FilamentDeveloperGate\Http\Middleware\DeveloperGateMiddleware;

/**
 * Sends the Artisan page through the developer gate when it is enabled for the current panel
 * (plugin ->developerGate() or the `filament-artisan.developer_gate` config).
 */
class ArtisanDeveloperGateMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Artisan::shouldUseDeveloperGate(Filament::getCurrentPanel())) {
            return $next($request);
        }

        return app(DeveloperGateMiddleware::class)->handle($request, $next);
    }
}
