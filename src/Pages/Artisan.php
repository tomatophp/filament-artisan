<?php

namespace TomatoPHP\FilamentArtisan\Pages;

use BackedEnum;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan as ArtisanFacade;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Output\BufferedOutput;
use TomatoPHP\FilamentArtisan\FilamentArtisanPlugin;
use TomatoPHP\FilamentArtisan\Http\Middleware\ArtisanDeveloperGateMiddleware;
use TomatoPHP\FilamentArtisan\Models\Command;
use TomatoPHP\FilamentDeveloperGate\Actions\DeveloperLogoutAction;
use UnitEnum;

class Artisan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected string $view = 'filament-artisan::index';

    protected static function getPlugin(?Panel $panel = null): ?FilamentArtisanPlugin
    {
        $panel ??= Filament::getCurrentPanel();

        if (! $panel?->hasPlugin('filament-artisan')) {
            return null;
        }

        /** @var FilamentArtisanPlugin */
        return $panel->getPlugin('filament-artisan');
    }

    public static function shouldUseDeveloperGate(?Panel $panel = null): bool
    {
        return static::getPlugin($panel)?->shouldUseDeveloperGate()
            ?? (bool) config('filament-artisan.developer_gate', true);
    }

    public static function isOnlyLocal(): bool
    {
        return static::getPlugin()?->isOnlyLocal() ?? (bool) config('filament-artisan.local', true);
    }

    /**
     * The configured `filament-artisan.middlewares` plus the developer gate. The gate middleware
     * is always attached and decides per request, so config / plugin changes apply without
     * re-registering (or re-caching) the routes.
     */
    public static function getRouteMiddleware(Panel $panel): string|array
    {
        return array_values(array_unique([
            ...Arr::wrap(parent::getRouteMiddleware($panel)),
            ...Arr::wrap(config('filament-artisan.middlewares', ['web', 'auth'])),
            ArtisanDeveloperGateMiddleware::class,
        ]));
    }

    public static function canAccess(): bool
    {
        if (static::isOnlyLocal() && ! App::environment('local')) {
            return false;
        }

        if (! (static::getPlugin()?->isAuthorized() ?? true)) {
            return false;
        }

        return parent::canAccess();
    }

    public function getTitle(): string
    {
        return trans('filament-artisan::messages.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-artisan::messages.title');
    }

    public static function setNavigationGroup(?string $navigationGroup): void
    {
        static::$navigationGroup = $navigationGroup ?? trans('filament-artisan::messages.group');
    }

    public static function shouldRegisterNavigation(): bool
    {
        if (config('filament-artisan.navigation.show-only-commands-showing', false)) {
            return static::canAccess();
        }

        return true;
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return strval(__(config('filament-artisan.navigation.group') ?? static::$navigationGroup));
    }

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return config('filament-artisan.navigation.icon') ?? static::$navigationIcon;
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            $this->outputAction(),
        ];

        if (static::shouldUseDeveloperGate()) {
            $actions[] = DeveloperLogoutAction::make();
        }

        return $actions;
    }

    public function outputAction(): Action
    {
        return Action::make('output')
            ->icon('heroicon-s-computer-desktop')
            ->color('warning')
            ->label(trans('filament-artisan::messages.actions.output'))
            ->modalHeading(trans('filament-artisan::messages.actions.output'))
            ->modalContent(fn (array $arguments = []) => view('filament-artisan::actions.output', [
                'output' => (string) ($arguments['output'] ?? session()->get('terminal_output', '')),
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(trans('filament-artisan::messages.actions.close'));
    }

    public function runAction(): Action
    {
        return Action::make('run')
            ->label(trans('filament-artisan::messages.modal.label'))
            ->requiresConfirmation()
            ->view('filament-artisan::actions.run')
            ->schema(function (array $arguments = []): array {
                $command = Command::query()->where('name', $arguments['item'] ?? null)->first();

                if (! $command) {
                    return [];
                }

                $fields = [];

                foreach ((array) json_decode($command->arguments ?? 'null') as $argument) {
                    $fields[] = $this->makeField($argument, (bool) $argument->required);
                }

                foreach ((array) json_decode($command->options ?? 'null') as $option) {
                    $fields[] = $this->makeField($option, false);
                }

                return $fields;
            })
            ->action(function (array $arguments = [], array $data = []): void {
                $output = $this->runCommand((string) ($arguments['item'] ?? ''), $data);

                $this->replaceMountedAction('output', ['output' => $output]);
            });
    }

    protected function makeField(object $definition, bool $isRequired): TagsInput|TextInput
    {
        if ($definition->array) {
            return TagsInput::make($definition->name)
                ->hint($definition->description)
                ->label($definition->title)
                ->default($definition->default)
                ->required($isRequired);
        }

        return TextInput::make($definition->name)
            ->password($definition->name === 'password')
            ->email($definition->name === 'email')
            ->tel($definition->name === 'phone')
            ->hint($definition->description)
            ->default(is_array($definition->default) ? null : $definition->default)
            ->label($definition->title)
            ->required($isRequired);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Command::query())
            ->paginated(false)
            ->deferFilters(config('filament-artisan.defer.filters', false))
            ->deferColumnManager(config('filament-artisan.defer.columns', false))
            ->content(fn () => view('filament-artisan::table.content'))
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('group')
                    ->label('Filter By Group')
                    ->searchable()
                    ->options(
                        fn () => Command::query()
                            ->select('group')
                            ->distinct()
                            ->pluck('group')
                            ->mapWithKeys(fn ($group) => [$group => $group])
                            ->all()
                    ),
            ])
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('description'),
                TextColumn::make('synopsis'),
                TextColumn::make('arguments'),
                TextColumn::make('options'),
                TextColumn::make('group')->searchable()->sortable(),
                TextColumn::make('error'),
            ]);
    }

    /**
     * The command names listed in `filament-artisan.commands`; nothing else may be run from the page.
     *
     * @return array<int, string>
     */
    public static function getAllowedCommands(): array
    {
        return array_values(array_filter(
            Arr::flatten((array) config('filament-artisan.commands', [])),
            fn ($name): bool => is_string($name) && filled($name),
        ));
    }

    protected function findCommandOrFail(string $name): SymfonyCommand
    {
        if (! in_array($name, static::getAllowedCommands(), true)) {
            abort(403);
        }

        $commands = ArtisanFacade::all();

        if (! array_key_exists($name, $commands)) {
            abort(404);
        }

        return $commands[$name];
    }

    public function runCommand(string $key, array $data = []): string
    {
        abort_unless(static::canAccess(), 403);

        $command = $this->findCommandOrFail($key);

        $permission = config('filament-artisan.permissions', [])[$command->getName()] ?? null;

        if ($permission && ! Gate::check($permission)) {
            abort(403);
        }

        try {
            $definition = $command->getDefinition();
            $params = [];

            foreach (array_filter($data, fn ($value): bool => filled($value)) as $name => $value) {
                if ($name === 'command') {
                    continue;
                }

                if ($definition->hasArgument($name)) {
                    $params[$name] = $value;
                } elseif ($definition->hasOption($name)) {
                    $params["--{$name}"] = $value;
                }
            }

            $output = new BufferedOutput;
            ArtisanFacade::call($command->getName(), $params, $output);
            $output = $output->fetch();

            session()->put('terminal_output', $output);

            Notification::make()
                ->title(trans('filament-artisan::messages.notifications.success.title'))
                ->body(trans('filament-artisan::messages.notifications.success.body'))
                ->success()
                ->send();

            return $output;
        } catch (Exception $exception) {
            session()->put('terminal_output', $exception->getMessage());

            Notification::make()
                ->title(trans('filament-artisan::messages.notifications.error.title'))
                ->body(trans('filament-artisan::messages.notifications.error.body'))
                ->danger()
                ->send();

            return $exception->getMessage();
        }
    }
}
