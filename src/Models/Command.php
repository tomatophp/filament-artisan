<?php

namespace TomatoPHP\FilamentArtisan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Sushi\Sushi;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @property string $name
 * @property ?string $description
 * @property ?string $synopsis
 * @property ?string $arguments
 * @property ?string $options
 * @property ?string $group
 * @property ?string $error
 */
class Command extends Model
{
    use Sushi;

    /**
     * @return array<string, string>
     */
    public function getSchema(): array
    {
        return [
            'name' => 'string',
            'description' => 'string',
            'synopsis' => 'string',
            'arguments' => 'json',
            'options' => 'json',
            'group' => 'string',
            'error' => 'string',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getRows(): array
    {
        return $this->prepareToJson(config('filament-artisan.commands', []));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function prepareToJson(array $commands): array
    {
        $commands = $this->renameKeys($commands);
        $defined = Artisan::all();

        $permissions = config('filament-artisan.permissions', []);

        foreach ($commands as $gKey => $group) {
            foreach ($group as $cKey => $command) {
                if (($permission = $permissions[$command] ?? null) && ! Gate::check($permission)) {
                    unset($commands[$gKey][$cKey]);

                    continue;
                }

                $commands[$gKey][$cKey] = $this->commandToArray($defined[$command] ?? $command);
            }

            $commands[$gKey] = array_values($commands[$gKey]);

            if (! $commands[$gKey]) {
                unset($commands[$gKey]);
            }
        }

        $rows = [];

        foreach ($commands as $key => $commandItem) {
            foreach ($commandItem as $singleCommand) {
                $singleCommand['group'] = $key;
                $rows[] = $singleCommand;
            }
        }

        return $rows;
    }

    protected function argumentsToArray(SymfonyCommand $command): ?array
    {
        $arguments = array_map(fn (InputArgument $argument): array => [
            'title' => Str::of($argument->getName())->replace('_', ' ')->title()->toString(),
            'name' => $argument->getName(),
            'description' => $argument->getDescription(),
            'default' => empty($default = $argument->getDefault()) ? null : $default,
            'required' => $argument->isRequired(),
            'array' => $argument->isArray(),
        ], $command->getDefinition()->getArguments());

        return empty($arguments) ? null : array_values($arguments);
    }

    protected function commandToArray(SymfonyCommand|string|null $command): ?array
    {
        if ($command === null) {
            return null;
        }

        if (! $command instanceof SymfonyCommand) {
            return [
                'name' => $command,
                'description' => null,
                'synopsis' => null,
                'arguments' => null,
                'options' => null,
                'error' => 'Not found',
            ];
        }

        return [
            'name' => $command->getName(),
            'description' => $command->getDescription(),
            'synopsis' => $command->getSynopsis(),
            'arguments' => json_encode($this->argumentsToArray($command)),
            'options' => json_encode($this->optionsToArray($command)),
            'error' => null,
        ];
    }

    protected function optionsToArray(SymfonyCommand $command): ?array
    {
        $options = array_map(fn (InputOption $option): array => [
            'title' => Str::of($option->getName())->replace('_', ' ')->title()->toString(),
            'name' => $option->getName(),
            'description' => $option->getDescription(),
            'shortcut' => $option->getShortcut(),
            'required' => $option->isValueRequired(),
            'array' => $option->isArray(),
            'accept_value' => $option->acceptValue(),
            'default' => empty($default = $option->getDefault()) ? null : $default,
        ], $command->getDefinition()->getOptions());

        return empty($options) ? null : array_values($options);
    }

    protected function renameKeys(array $array): array
    {
        $keys = array_map(fn ($key): string => Str::title($key), array_keys($array));

        return array_combine($keys, array_values($array));
    }
}
