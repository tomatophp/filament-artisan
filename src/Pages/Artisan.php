<?php

namespace TomatoPHP\FilamentArtisan\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\App;
use TomatoPHP\FilamentArtisan\Http\Controllers\GuiController;

class Artisan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-code';

    protected static string $view = 'gui::index';

    protected static ?string $navigationGroup = 'Settings';

    public static function shouldRegisterNavigation(): bool
    {
        $show = true;
        if (config('filament-artisan.navigation.show-only-commands-showing', false)) {
            $local = App::environment('local');
            $only = config('filament-artisan.local', true);
            $show = ($local || !$only);
        }

        return $show && static::hasCommands();
    }

    public static function getNavigationGroup(): ?string
    {
        return strval(__(config('filament-artisan.navigation.group') ?? static::$navigationGroup));
    }

    public function mount(): void
    {
        abort_unless(static::hasCommands(), 403);
    }

    private static function hasCommands(): bool
    {
        return !empty((new GuiController())->prepareTojson(config('filament-artisan.commands', [])));
    }
}
