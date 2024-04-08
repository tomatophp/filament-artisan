![Screenshot](./arts/screenshot.png)

# Filament artisan

Simple but yet powerful library for running some [artisan](https://laravel.com/docs/8.x/artisan) commands.
this packages is a fork of [artisan-gui](https://github.com/infureal/artisan-gui) with some custom for filament UI

## Installation

```bash
composer require tomatophp/filament-artisan
```
after install your package please run this command

```bash
php artisan filament-artisan:install
```

By default package has predefined config and inline styles and scripts.
Since version `1.4` you can publish vendors like css and js files in `vendor/artisan-gui`:
```bash
php artisan vendor:publish --provider="TomatoPHP\FilamentArtisan\FilamentArtisanProvider"
```

Publish only config:
```bash
php artisan vendor:publish --tag="artisan-gui-config"
```

Publish only styles and scripts:

```bash
php artisan vendor:publish --tag="artisan-gui-css-js"
```

## Running command
By default, you can access this page only in local environment. If you wish
you can change `local` key in config.

Simply go to `http://you-domain.com/admin/artisan` and here we go!
Select needed command from list, fill arguments and options/flags and hit `run` button.

## Configuration
Default config is:
```php 
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Middleware list for web routes
    |--------------------------------------------------------------------------
    |
    | You can pass any middleware for routes, by default it's just [web] group
    | of middleware.
    |
    */
    'middlewares' => [
        'web',
//        'auth'
    ],

    /*
    |--------------------------------------------------------------------------
    | Route prefix
    |--------------------------------------------------------------------------
    |
    | Prefix for gui routes. By default url is [/~artisan-gui].
    | For your wish you can set it for example 'my-'. So url will be [/my-artisan-gui].
    |
    | Why tilda? It's selected for prevent route names correlation.
    |
    */
    'prefix' => '~',

    /*
    |--------------------------------------------------------------------------
    | Home url
    |--------------------------------------------------------------------------
    |
    | Where to go when [home] button is pressed
    |
    */
    'home' => '/',

    /*
    |--------------------------------------------------------------------------
    | Only on local
    |--------------------------------------------------------------------------
    |
    | Flag that preventing showing commands if environment is on production
    |
    */
    'local' => true,

    /*
    |--------------------------------------------------------------------------
    | List of commands
    |--------------------------------------------------------------------------
    |
    | List of all default commands that has end of execution. Commands like
    | [serve] not supported in case of server side behavior of php.
    | Keys means group. You can shuffle commands as you wish and add your own.
    |
    */
    'commands' => [
        // ...
    ]

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

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-artisan-migrations"
```

## Support

you can join our discord server to get support [TomatoPHP](https://discord.gg/Xqmt35Uh)

## Docs

you can check docs of this package on [Docs](https://docs.tomatophp.com/plugins/laravel-package-generator)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
