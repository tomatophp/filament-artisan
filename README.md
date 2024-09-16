![Screenshot](https://raw.githubusercontent.com//tomatophp/filament-artisan/master/arts/3x1io-tomato-artisan.jpg)

# Filament Artisan Command Runner

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-artisan/version.svg)](https://packagist.org/packages/tomatophp/filament-artisan)
[![License](https://poser.pugx.org/tomatophp/filament-artisan/license.svg)](https://packagist.org/packages/tomatophp/filament-artisan)
[![Downloads](https://poser.pugx.org/tomatophp/filament-artisan/d/total.svg)](https://packagist.org/packages/tomatophp/filament-artisan)

Simple but yet powerful library for running some [artisan](https://laravel.com/docs/8.x/artisan) commands for FilamentPHP

## Screenshots

![Commands List](https://raw.githubusercontent.com/tomatophp/filament-artisan/master/arts/commands.png)
![Commands Form](https://raw.githubusercontent.com/tomatophp/filament-artisan/master/arts/commands-form.png)
![Commands Output](https://raw.githubusercontent.com/tomatophp/filament-artisan/master/arts/command-output.png)

## Installation

```bash
composer require tomatophp/filament-artisan
```

finally reigster the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentArtisan\FilamentArtisanPlugin::make())
```

## Running command

By default, you can access this page only in local environment. If you wish
you can change `local` key in config.

Simply go to `http://you-domain.com/PANEL/artisan` and here we go!
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

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
