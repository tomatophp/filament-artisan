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
        'auth'
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
    'prefix' => 'admin/',

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
    | Developer gate
    |--------------------------------------------------------------------------
    |
    | Flag to disable or enable developer gate
    |
    */
    'developer_gate' => true,

    /*
    |--------------------------------------------------------------------------
    | List of command permissions
    |--------------------------------------------------------------------------
    |
    | Specify permissions to every single command. Can be a string or array
    | of permissions
    |
    | Example:
    |   'make:controller' => 'create-controller',
    |   'make:event' => ['generate-files', 'create-event'],
    |
    */
    'permissions' => [],

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
        'filament' => [
            'filament:assets',
            'filament:cache-components',
            'filament:check-translations',
            'filament:clear-cached-components',
            'filament:helpers',
            'filament:upgrade',
            'make:filament-cluster',
            'make:filament-exporter',
            'make:filament-issue',
            'make:filament-page',
            'make:filament-panel',
            'make:filament-relation-manager',
            'make:filament-resource',
            'make:filament-settings-page',
            'make:filament-theme',
            'make:filament-user',
            'make:filament-widget',
        ],
        'livewire' => [
            'livewire:attribute',
            'livewire:configure-s3-upload-cleanup',
            'livewire:copy',
            'livewire:delete',
            'livewire:form',
            'livewire:layout',
            'livewire:make',
            'livewire:move',
            'livewire:publish',
            'livewire:stubs',
            'livewire:upgrade'
        ],
        'icons' => [
            'icons:cache',
            'icons:clear'
        ],
        'laravel' => [
            'clear-compiled',
            'down',
            'up',
            'env',
            'help',
            'list',
            'notifications:table',
            'package:discover',
            'schedule:run',
            'schema:dump',
            'session:table',
            'storage:link',
            'stub:publish',
            'auth:clear-resets',
        ],
        'optimize' => [
            'optimize',
            'optimize:clear',
        ],
        'cache' => [
            'cache:clear',
            'cache:forget',
            'cache:table',
            'config:clear',
            'config:cache',
        ],
        'database' => [
            'db:seed',
            'db:wipe',
        ],
        'events' => [
            'event:cache',
            'event:clear',
            'event:generate',
            'event:list',
        ],
        'make' => [
            'make:cast',
            'make:channel',
            'make:command',
            'make:component',
            'make:controller',
            'make:event',
            'make:exception',
            'make:factory',
            'make:job',
            'make:listener',
            'make:mail',
            'make:middleware',
            'make:migration',
            'make:model',
            'make:notification',
            'make:observer',
            'make:policy',
            'make:provider',
            'make:request',
            'make:resource',
            'make:rule',
            'make:seeder',
            'make:test',
        ],
        'migrate' => [
            'migrate',
            'migrate:fresh',
            'migrate:install',
            'migrate:refresh',
            'migrate:reset',
            'migrate:rollback',
            'migrate:status',
        ],
        'queue' => [
            'queue:batches-table',
            'queue:clear',
            'queue:failed',
            'queue:failed-table',
            'queue:flush',
            'queue:forget',
            'queue:restart',
            'queue:retry',
            'queue:retry-batch',
            'queue:table',
        ],
        'route' => [
            'route:cache',
            'route:clear',
            'route:list',
        ],
        'view' => [
            'view:cache',
            'view:clear'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    |
    | Specify settings for the navigation.
    |
    |   show-only-commands-showing:
    |       if set true, hide in the navigation if the commands are not shown.
    |   group:
    |       set the group name for the navigation (will be translate).
    */
    'navigation' => [
        'show-only-commands-showing' => true,
        'group' => 'Settings',
        'icon' => 'heroicon-o-command-line',
    ]
];
