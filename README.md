![Screenshot](https://raw.githubusercontent.com//tomatophp/filament-artisan/master/arts/3x1io-tomato-artisan.jpg)

# Filament Artisan Command Runner

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-artisan/version.svg)](https://packagist.org/packages/tomatophp/filament-artisan)
[![License](https://poser.pugx.org/tomatophp/filament-artisan/license.svg)](https://packagist.org/packages/tomatophp/filament-artisan)
[![Downloads](https://poser.pugx.org/tomatophp/filament-artisan/d/total.svg)](https://packagist.org/packages/tomatophp/filament-artisan)

Simple but yet powerful library for running some [artisan](https://laravel.com/docs/artisan) commands for FilamentPHP

## Screenshots

![Commands List](https://raw.githubusercontent.com/tomatophp/filament-artisan/master/arts/commands.png)
![Commands Form](https://raw.githubusercontent.com/tomatophp/filament-artisan/master/arts/commands-form.png)
![Commands Output](https://raw.githubusercontent.com/tomatophp/filament-artisan/master/arts/command-output.png)

## Compatibility

| Package version | Filament | Laravel | PHP |
|-----------------|----------|---------|-----|
| 5.x             | 5.x      | 12.x, 13.x | 8.2+ |
| 1.x             | 3.x      | 10.x, 11.x | 8.1+ |

## Installation

```bash
composer require tomatophp/filament-artisan
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentArtisan\FilamentArtisanPlugin::make())
```

The plugin also registers the [Developer Gate](https://github.com/tomatophp/filament-developer-gate) page on the panel,
so set a strong gate password in your `.env`:

```dotenv
DEVELOPER_GATE_PASSWORD=change-me
```

## Running command

Go to `https://your-domain.com/PANEL/artisan`, select the needed command from the list, fill the arguments and
options/flags and hit `run`. The output is shown in a modal once the command finished.

## Security

This page runs artisan commands on your server, so it is locked down by default:

- **Local only**: with `'local' => true` (the default) the page is only accessible, and only shown in the navigation,
  when `APP_ENV=local`. Set `'local' => false` in the published config (or use `->onlyLocal(false)`) to use it on other environments.
- **Developer gate**: with `'developer_gate' => true` (the default) the page asks for `DEVELOPER_GATE_PASSWORD` first.
- **Whitelist**: only the commands listed in the `commands` config key can be run. Any other registered command is refused with a 403.
- **Permissions**: the `permissions` config key maps a command to a Gate ability, e.g. `'migrate:fresh' => 'run-destructive-commands'`.
- **Route middleware**: the `middlewares` config key is applied to the page route (add `'verified'` if your users verify their email).

You can restrict or disable the page per panel from the plugin:

```php
->plugin(
    \TomatoPHP\FilamentArtisan\FilamentArtisanPlugin::make()
        // only super admins may open the page (pass false to disable it entirely)
        ->authorize(fn (): bool => auth()->user()?->hasRole('super_admin') ?? false)
        // override the `developer_gate` config for this panel
        ->developerGate(true)
        // override the `local` config for this panel
        ->onlyLocal(false)
)
```

The default command list contains destructive commands (`migrate:fresh`, `db:wipe`, `down`, ...). On anything that is not
your own machine, publish the config and trim the `commands` list to what you really need.

## Configuration

Publish the config file to change the defaults:

```bash
php artisan vendor:publish --tag="filament-artisan-config"
```

```php
return [
    // route middleware applied to the page, the developer gate is appended when enabled
    'middlewares' => ['web', 'auth'],

    // only allow the page when APP_ENV=local
    'local' => true,

    // protect the page with the developer gate password
    'developer_gate' => true,

    // command => Gate ability(ies)
    'permissions' => [],

    // group => [commands], the whitelist of commands the page can run
    'commands' => [
        // ...
    ],

    'navigation' => [
        // hide the navigation item when the page is not accessible
        'show-only-commands-showing' => true,
        'group' => 'Settings',
        'icon' => 'heroicon-o-command-line',
    ],

    // defer the table filters and the column manager
    'defer' => [
        'filters' => false,
        'columns' => false,
    ],
];
```

and now clear cache

```bash
php artisan optimize:clear
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-artisan-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-artisan-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-artisan-lang"
```

## Testing

```bash
composer test
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
