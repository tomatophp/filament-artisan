<?php

namespace TomatoPHP\FilamentArtisan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Sushi\Sushi;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends Model
{
    use Sushi;

    public function getSchema(): array
    {
        return [
            "name" => "string",
            "description" => "string",
            "synopsis" => "string",
            "arguments" => "json",
            "options" => "json",
            "group" => "string",
            "error" => "string"
        ];
    }

    public function getRows()
    {
        return $this->prepareToJson(config('filament-artisan.commands', []));
    }

    public function prepareToJson(array $commands): array
    {
        $commands = $this->renameKeys($commands);
        $defined = Artisan::all();

        $permissions = config('filament-artisan.permissions', []);

        foreach ($commands as $gKey => $group) {
            foreach ($group as $cKey => $command) {

                if (($permission = $permissions[$command] ?? null) && !\Gate::check($permission)) {
                    unset($commands[$gKey][$cKey]);
                    continue;
                }

                $commands[$gKey][$cKey] = $this->commandToArray($defined[$command] ?? $command);
            }
            $commands[$gKey] = array_values($commands[$gKey]);

            if (!$commands[$gKey]) {
                unset($commands[$gKey]);
            }
        }

        $getCommandArray = [];
        foreach ($commands as $key=>$commandItem){
            foreach ($commandItem as $singleCommand){
                $singleCommand['group'] = $key;
                $getCommandArray[] = $singleCommand;
            }
        }

        return $getCommandArray;
    }

    protected function argumentsToArray(\Symfony\Component\Console\Command\Command $command): ?array
    {
        $definition = $command->getDefinition();
        $arguments = array_map(function (InputArgument $argument) {
            return [
                'title' => \Str::of($argument->getName())->replace('_', ' ')->title()->__toString(),
                'name' => $argument->getName(),
                'description' => $argument->getDescription(),
                'default' => empty($default = $argument->getDefault()) ? null : $default,
                'required' => $argument->isRequired(),
                'array' => $argument->isArray(),
            ];
        }, $definition->getArguments());

        return empty($arguments) ? null : $arguments;
    }


    protected function commandToArray($command): ?array
    {

        if ($command === null)
            return  null;

        if (!$command instanceof \Symfony\Component\Console\Command\Command)
            return [
                'name' => $command,
                'description' => null,
                'synopsis' => null,
                'arguments' => null,
                'options' => null,
                'error' => 'Not found'
            ];

        return [
            'name' => $command->getName(),
            'description' => $command->getDescription(),
            'synopsis' => $command->getSynopsis(),
            'arguments' => json_encode($this->argumentsToArray($command)),
            'options' => json_encode($this->optionsToArray($command)),
            'error' => null
        ];
    }

    protected function optionsToArray(\Symfony\Component\Console\Command\Command $command): ?array
    {
        $definition = $command->getDefinition();

        $options = array_map(function (InputOption $option) {
            return [
                'title' => \Str::of($option->getName())->replace('_', ' ')->title()->__toString(),
                'name' => $option->getName(),
                'description' => $option->getDescription(),
                'shortcut' => $option->getShortcut(),
                'required' => $option->isValueRequired(),
                'array' => $option->isArray(),
                'accept_value' => $option->acceptValue(),
                'default' => empty($default = $option->getDefault()) ? null : $default,
            ];
        }, $definition->getOptions());

        return empty($options) ? null : $options;
    }

    protected function renameKeys(array $array): array
    {
        $keys = array_map(function ($key) {
            return \Str::title($key);
        }, array_keys($array));

        return array_combine($keys, array_values($array));
    }


}
