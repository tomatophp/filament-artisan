<?php

use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan as ArtisanFacade;
use TomatoPHP\FilamentArtisan\Http\Middleware\ArtisanDeveloperGateMiddleware;
use TomatoPHP\FilamentArtisan\Pages\Artisan;
use TomatoPHP\FilamentArtisan\Tests\Models\User;
use TomatoPHP\FilamentDeveloperGate\Pages\DeveloperGate;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('renders the artisan page', function () {
    get(Artisan::getUrl())->assertSuccessful()->assertSee('route:list');

    livewire(Artisan::class)->assertSuccessful()->assertSee('route:list');
});

it('honours the configured route middleware instead of a hardcoded list', function () {
    config()->set('filament-artisan.middlewares', ['web', 'auth']);

    expect(Artisan::getRouteMiddleware(Filament::getPanel('admin')))
        ->toContain('web', 'auth', ArtisanDeveloperGateMiddleware::class)
        ->not->toContain('verified');

    config()->set('filament-artisan.middlewares', ['web', 'auth', 'verified']);

    expect(Artisan::getRouteMiddleware(Filament::getPanel('admin')))->toContain('verified');
});

it('redirects to the developer gate of the current panel when the gate is enabled', function () {
    config()->set('filament-artisan.developer_gate', true);

    $gateUrl = DeveloperGate::getUrl();

    expect($gateUrl)->toEndWith('/app/developer-gate');

    get(Artisan::getUrl())->assertRedirect($gateUrl);

    get($gateUrl)->assertSuccessful();
});

it('opens the page once the developer gate password was entered', function () {
    config()->set('filament-artisan.developer_gate', true);

    session()->put('developer_password', config('filament-developer-gate.password'));

    get(Artisan::getUrl())->assertSuccessful();
});

it('sends the gate logout back to the gate of the current panel', function () {
    config()->set('filament-artisan.developer_gate', true);

    livewire(Artisan::class)
        ->callAction('developer_gate_logout')
        ->assertRedirect(DeveloperGate::getUrl());

    expect(session()->has('developer_password'))->toBeFalse();
});

it('runs a whitelisted command and shows its output', function () {
    livewire(Artisan::class)
        ->callAction(TestAction::make('run')->arguments(['item' => 'route:list']), ['json' => '1'])
        ->assertActionMounted('output')
        ->assertNotified();

    $routes = collect(json_decode(session('terminal_output'), true));

    expect($routes->pluck('uri'))->toContain('app/artisan');
});

it('passes form data as arguments and options to the command', function () {
    livewire(Artisan::class)->instance()->runCommand('help', ['command_name' => 'about', 'format' => 'json']);

    expect(json_decode(session('terminal_output'), true))->toHaveKey('name', 'about');
});

it('refuses a command that is registered but not whitelisted', function () {
    expect(ArtisanFacade::all())->toHaveKey('db:show');
    expect(Artisan::getAllowedCommands())->not->toContain('db:show');

    livewire(Artisan::class)
        ->callAction(TestAction::make('run')->arguments(['item' => 'db:show']))
        ->assertForbidden();

    expect(session()->has('terminal_output'))->toBeFalse();
});

it('respects the per command permissions', function () {
    config()->set('filament-artisan.permissions', ['route:list' => 'run-route-list']);

    livewire(Artisan::class)
        ->callAction(TestAction::make('run')->arguments(['item' => 'route:list']))
        ->assertForbidden();
});

it('escapes the command output', function () {
    $html = view('filament-artisan::actions.output', ['output' => '<script>alert(1)</script>'])->render();

    expect($html)->not->toContain('<script>alert(1)</script>')
        ->toContain('&lt;script&gt;alert(1)&lt;/script&gt;');

    livewire(Artisan::class)
        ->mountAction(TestAction::make('output')->arguments(['output' => '<script>alert(1)</script>']))
        ->assertActionMounted('output');
});

it('is only available on local environments when the local flag is on', function () {
    config()->set('filament-artisan.local', true);

    expect(Artisan::canAccess())->toBeFalse();
    get(Artisan::getUrl())->assertForbidden();

    app()->detectEnvironment(fn (): string => 'local');

    expect(Artisan::canAccess())->toBeTrue();
});

it('can be disabled through the plugin', function () {
    Filament::getPanel('admin')->getPlugin('filament-artisan')->authorize(false);

    expect(Artisan::canAccess())->toBeFalse();
    get(Artisan::getUrl())->assertForbidden();
    expect(Artisan::shouldRegisterNavigation())->toBeFalse();

    Filament::getPanel('admin')->getPlugin('filament-artisan')->authorize(true);
});
